<?php
require_once(dirname(__FILE__) . "/exception.php");

//config. parses okta.config.xml; stores all neccessary urls and filenames settings
abstract class Config {
	const XML_CONFIG_FILENAME = "okta.config.xml";
	
    const LOGS_FILENAME = "okta.log";
	const LOGS_LEVEL = "DEBUG";
    
	private static $oktaUrl = null;
	private static $authUrl = null;
	private static $logoutUrl = null;
	private static $issuer = null;
	private static $cert = null;
	
	public static function getBaseUrl() {
		$endsWithSlash = strrpos($_SERVER['REQUEST_URI'], "/") == strlen($_SERVER['REQUEST_URI'])-1;
		if (!$endsWithSlash) {
        		$val = rtrim("http" .
                		(isset($_SERVER['HTTPS']) ? "s" : "") . 
                		"://" . $_SERVER["HTTP_HOST"], " \\/") .  
                		rtrim(pathinfo($_SERVER['REQUEST_URI'], PATHINFO_DIRNAME));
		} else {
        		$val = rtrim("http" .
                		(isset($_SERVER['HTTPS']) ? "s" : "") .
                		"://" . $_SERVER["HTTP_HOST"], " \\/") .
                		rtrim($_SERVER['REQUEST_URI']);
		}

		return $val;	
         }	
    public static function getOKTAUrl() {
        self::load();
        return self::$oktaUrl;
    }
    
    public static function getAuthUrl() {
		self::load();
        return self::$authUrl;
	}

    public static function getLogoutUrl() {
        self::load();
        return self::$logoutUrl;
	}

	public static function getCert() {
		self::load();
        return self::$cert;
	}
	
	public static function getIssuer() {
		self::load();
        return self::$issuer;
	}
	
	public static function getRelayStateURL() {
        $relayState = null;
        if (isset($_REQUEST["RelayState"])) {
            $relayState = @trim($_REQUEST["RelayState"]);
        } 
        
        if (!preg_match("/^http(s)?:\/\/[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i", $relayState)){
            trigger_error("RelayState was not found in web request or passed malformed", E_USER_NOTICE);
            $relayState = self::getBaseUrl() . "/sso";
        }
        return $relayState;
    }
    
	public static function isLoaded() {
		return !@empty(self::$authUrl);
	}
	
	public static function getXMLConfigContents() {
		return file_get_contents(dirname(dirname(__FILE__)) . "/" . self::XML_CONFIG_FILENAME);
	}

	public static function getLogsFilename() {
		return dirname(dirname(__FILE__)) . "/logs/" . self::LOGS_FILENAME;
	}
	
    //loads config data from file
    //throws exception if failed
    public static function load($configFile = null, $forceReload = false) {
		$configFile = (!$configFile ? dirname(dirname(__FILE__)) . "/" . self::XML_CONFIG_FILENAME : $configFile);		
        if (self::isLoaded() && !$forceReload) {
			return;
		}
		
		if (!file_exists($configFile)) {
            throw new PHPSAMLException ("OKTA config was not found");
        }
        
        if (!is_readable($configFile)) {
			throw new PHPSAMLException ("OKTA config is not readable");
		}
		try {
            $xml = new SimpleXMLElement(file_get_contents($configFile));
        } catch (Exception $e) {
            throw new PHPSAMLException("Malformed OKTA config. File cannot be parsed as XML");
            
        }
        
		@list($url) = $xml->xpath("/configuration/okta/authentication/authenticationUrl");
		if (@is_null($url)) {			
			@list($url) = $xml->xpath("/configuration/okta/authentication/authenticationurl");
		}
		
		self::$authUrl = (string)$url;
        if (@empty(self::$authUrl)) {
            throw new PHPSAMLException("Malformed XML in OKTA config. " . 
                                        "/configuration/okta/authentication/authenticationurl node was not found or empty");
        }
        
        
		@list($url) = $xml->xpath("/configuration/okta/authentication/logoutUrl");

		if ( @is_null($url)) {
			@list($url) = $xml->xpath("/configuration/okta/authentication/logouturl");
		}
		self::$logoutUrl = (string)$url;
        if (@empty(self::$logoutUrl)) {
            throw new PHPSAMLException("Malformed XML in OKTA config. " . 
                                        "/configuration/okta/authentication/logoutUrl node was not found or empty");
        }


		@list($issuer) = $xml->xpath("/configuration/okta/authentication/issuer");
		self::$issuer = (string)$issuer;
        if (@empty(self::$issuer)) {
            throw new PHPSAMLException("Malformed XML in OKTA config. " . 
                                        "/configuration/okta/authentication/issuer node was not found or empty");
        }
		
		@list($cert) = $xml->xpath("/configuration/okta/authentication/certificate");
		self::$cert = @trim((string)$cert);
        if (@empty(self::$cert)) {
            throw new PHPSAMLException("Malformed XML in OKTA config. " . 
                                        "/configuration/okta/authentication/certificate node was not found or empty");
        }
        $urlParts = parse_url(self::$authUrl);
        if (!$urlParts) {
            throw new PHPSAMLException("Malformed XML in OKTA config. " . 
                                        "authentication url could not be parsed");
        }
        
        self::$oktaUrl = $urlParts["scheme"] . "://" . $urlParts["host"];
        if (!@empty($urlParts["port"])) {
            self::$oktaUrl .= ":" . $urlParts["port"];
        }     
	}
}
