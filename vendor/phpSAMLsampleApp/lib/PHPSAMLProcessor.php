<?php
require_once(dirname(__FILE__) . "/thirdparty/xmlseclibs.php");
require_once(dirname(__FILE__) . "/config.php");
require_once(dirname(__FILE__) . "/exception.php");

error_reporting(E_ALL | E_WARNING | E_NOTICE);

/*
 main class that recieves requests from okta and handles responses
 implements singleton pattern so constructor is private and class instance is accessible by PHPSAMLProcessor::self()
*/

class PHPSAMLProcessor
{
    private static $self;
    private $SAMLattributes;
    //authenticated user id (string)
    private $authenticatedUserId;

    //key in session map
    const UID_KEY = "userId";
    const ATTRS_KEY = "attrs";

    private function __construct()
    {
        $this->authenticatedUserId = null;
    }

    //use this method to access processor class instance
    // example: PHPSAMLProcessor::self()->init()
    public static function self()
    {
        if (!self::$self) {
            self::$self = new PHPSAMLProcessor();
        }
        return self::$self;
    }

    //method for internal usage.
    //just starts the session if it wasn't started before
    private function startSession()
    {
        $sessId = session_id();
        if (empty($sessId)) {
            session_start();
        }
    }

    //method for storing userid or smth else in session
    //saves value to the session by the specified key
    private function sessionStore($key, $val)
    {
        $this->startSession();
        $_SESSION[$key] = $val;
    }

    //method for fetching userid or smth else in session
    //fetch value from session map by key
    private function sessionFetch($key)
    {
        $this->startSession();
        if (!isset($_SESSION[$key])) {
            trigger_error("Requested key: " . $key . " was not found in current SESSION", E_USER_NOTICE);
        }
        return $_SESSION[$key];
    }

    //clear session
    //unset user in session
    public function logout()
    {
        $this->startSession();
        unset($_SESSION[self::UID_KEY]);
        unset($_SESSION[self::ATTRS_KEY]);
    }

    public function getUserIdBySAMLResponse($encodedResponse)
    {
        $SAMLResponse = base64_decode(trim($encodedResponse));
        if (!$SAMLResponse) {
            throw new PHPSAMLException("Invalid SAMLResponse provided. Could not decode it from base64");
        }

        //validating XML signature. throws exception on failure
        $this->validateXMLSignature($SAMLResponse);

        //initializing SimpleXMLElement object with fetched xml from "SAMLResponse" post param
        $xml = new SimpleXMLElement($SAMLResponse);
        //using required xml namespaces
        $xml->registerXPathNamespace("samlp", "urn:oasis:names:tc:SAML:2.0:protocol");
        $xml->registerXPathNamespace("saml", "urn:oasis:names:tc:SAML:2.0:assertion");

        //fetching user from xml node by the strict path
        $userId = $xml->xpath("/samlp:Response/saml:Assertion/saml:Subject/saml:NameID");

        if (is_array($userId)) {
            $userId = (string)$userId[0];
        }

        if (@empty($userId)) {
            throw new PHPSAMLException("Failed fetching user from SAML response");
        }

        return $userId;
    }

    //init. handles requests; sends responses; basicly it is saml entry point
    public function init()
    {
        try {
            //parsing okta.config.xml and loading issuer, authUrl and cert into Config class
            Config::load();
            $relayState = null;
            if (isset($_REQUEST["RelayState"])) {
                $relayState = @trim($_REQUEST["RelayState"]);
            }

            $isDiag = (strpos($relayState, "diag.php") !== false);
            //$isDiag = true;

            //user already authenticated ?
            if ($this->isAuthenticated() && !$isDiag) {
                return;
            }

            //user exists in session ?
            $authUser = null;
            if (isset($_SESSION["userID"])) {
                $authUser = $_SESSION["userID"];
                Logger::getRootLogger()->info("Authenticated user: " . $authUser);
            }

            if (!@empty($authUser) && !$isDiag) {
                $this->setAuthenticatedUserId($_SESSION["userID"]);
                return;
            }

            //maybe user can be taken from post request?!
            $SAMLResponse = null;
            if (isset($_POST["SAMLResponse"])) {
                $SAMLResponse = $_POST["SAMLResponse"];
            }


            if (!empty($SAMLResponse)) {
                if ($isDiag) {
                    echo "<div style='color:#696969;'>" . nl2br(htmlspecialchars(base64_decode($SAMLResponse))) . "</div>";
                    exit();
                }

                //url to redirect if provided. otherwise default will be used                
                $userId = $this->getUserIdBySAMLResponse($SAMLResponse);
                Logger::getRootLogger()->info("Fetched user from SAMLReponse: " . $userId . ", relocating to relay state");
                if (!@empty($userId)) {
                    $this->getUserAttributes($SAMLResponse);
                }
                Logger::getRootLogger()->info("Fetched attributes from SAMLReponse");

                $this->setAuthenticatedUserId($userId);
                //redirecting to relayState url if provided or to the default if not relayState url 
                $this->redirect(Config::getRelayStateURL());
            } else {
                //requesting user data from okta
                $relayState = Config::getRelayStateURL();
                $redirUrl = Config::getAuthUrl() . "?SAMLRequest=" . urlencode(base64_encode($this->createSAMLRequest())) . "&RelayState=" . urlencode($relayState);
                Logger::getRootLogger()->info("Requesting user data from okta through SAMLRequest GET param");
                $this->redirect($redirUrl, $msg);
            }
        } catch (Exception $e) {
            Logger::getRootLogger()->error($e->getMessage());
        }
    }

    //method for safe redirect. if msg param provided, message will be shown and redirect will take place with delay in 2 seconds
    public function redirect($url, $msg = null)
    {


        if (@empty($msg)) {
            if (!headers_sent()) {
                Logger::getRootLogger()->info("redirecting to " . $url);
                header("Location: " . $url);
                exit;
            } else {
                Logger::getRootLogger()->error("headers sent, failed to redirect");
            }
        } else {
            $urlParts = parse_url($url);
            $curURI = trim($_SERVER["REQUEST_URI"]);
            $redirURI = trim($urlParts["path"]);

            if ($curURI == $redirURI) {
                throw new PHPSAMLException ("Recursive redirection detected. Seems PHP plugin configured incorrectly");
            }

            //showing msg before redirect
            echo '<html><head><meta http-equiv="refresh" content="2; url=' . $url . '"></head>' .
                '<body>' . $msg . '</body>' .
                '</html>';
            exit;
        }
    }

    //create samlRequest xml
    public function createSAMLRequest()
    {
        $xmlTpl = '<samlp:AuthnRequest ID="%s" Version="%s" IssueInstant="%s" ProtocolBinding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" AssertionConsumerServiceURL="%s" xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol">' . "\n" .
            '<saml:Issuer xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion">%s</saml:Issuer>' . "\n" .
            '<samlp:NameIDPolicy Format="urn:oasis:names:tc:SAML:2.0:nameid-format:unspecified" /><samlp:RequestedAuthnContext Comparison="exact" />' . "\n" .
            '<saml:AuthnContextClassRef xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion">urn:oasis:names:tc:SAML:2.0:ac:classes:PasswordProtectedTransport</saml:AuthnContextClassRef>' . "\n" .
            '</samlp:AuthnRequest>';

        $guid = uniqid();
        $ver = "2.0";
        $xml = sprintf($xmlTpl, $guid, $ver, date("c"), Config::getAuthUrl(), Config::getIssuer());
        return $xml;
    }


    //method for validating xml signature
    private function validateXMLSignature($xml)
    {
        Logger::getRootLogger()->info("Validating XML signature...");

        $cert = Config::getCert();

        $doc = new DOMDocument();
        if (!$doc->loadXML($xml)) {
            throw new PHPSAMLException("Failed loading DOM Document decoded from SAMLResponse");
        }


        $objXMLSecDSig = new XMLSecurityDSig();

        $objDSig = $objXMLSecDSig->locateSignature($doc);

        if (!$objDSig) {
            throw new PHPSAMLException("Cannot locate Signature Node in the SAMLResponse");
        }

        $objXMLSecDSig->canonicalizeSignedInfo();
        $objXMLSecDSig->idKeys = array('wsu:Id');
        $objXMLSecDSig->idNS = array('wsu' => 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd');

        $retVal = $objXMLSecDSig->validateReference();

        if (!$retVal) {
            throw new PHPSAMLException("Reference Validation Failed");
        }

        $objKey = $objXMLSecDSig->locateKey();
        if (!$objKey) {
            throw new PHPSAMLException("We have no idea about the key");
        }
        $key = NULL;

        $objKeyInfo = XMLSecEnc::staticLocateKeyInfo($objKey, $objDSig);

        if (!$objKeyInfo->key && empty($key)) {
            $objKey->loadKey($cert, false);
        }

        if (!$objXMLSecDSig->verify($objKey)) {
            throw new PHPSAMLException("XML verification failed");
        }

        Logger::getRootLogger()->info("Validation succeded");
    }

    //check if user is authenticated and stored in session
    public function isAuthenticated()
    {
        return ($this->getAuthenticatedUserId() != null);
    }

    //get authenticated and stored in session user
    public function getAuthenticatedUserId()
    {
        if (empty($this->authenticatedUserId)) {
            $this->authenticatedUserId = $this->sessionFetch(self::UID_KEY);
        }
        return $this->authenticatedUserId;
    }

    //set authenticated and stored in session user
    public function setAuthenticatedUserId($uid)
    {
        $this->authenticatedUserId = $uid;
        $this->sessionStore(self::UID_KEY, $this->authenticatedUserId);
    }

    //clear logs
    public function clearLogs()
    {
        file_put_contents(Config::getLogsFilename(), "");
        Logger::getRootLogger()->info("Logging started");
    }

    public function getLogs()
    {
        return file_get_contents(Config::getLogsFilename());
    }

    public function getAttribute($name)
    {
        return $this->SAMLattributes[$name];
    }

    private function getUserAttributes($encodedResponse)
    {

        $SAMLResponse = base64_decode(trim($encodedResponse));
        $dom = new DOMDocument();
        $dom->loadXML($SAMLResponse);
        $doc = $dom->documentElement;
        $xpath = new DOMXpath($dom);
        $xpath->registerNamespace('samlp', 'urn:oasis:names:tc:SAML:2.0:protocol');
        $xpath->registerNamespace('saml', 'urn:oasis:names:tc:SAML:2.0:assertion');
        foreach ($xpath->query('/samlp:Response/saml:Assertion/saml:AttributeStatement/saml:Attribute', $doc) as $attr) {
            foreach ($xpath->query('saml:AttributeValue', $attr) as $value) {
                $this->SAMLattributes[$attr->getAttribute('Name')] = $value->textContent;
            }
        }
        $this->sessionStore(self::ATTRS_KEY, $this->SAMLattributes);
    }

    public function getAttributes()
    {
        if (empty($this->SAMLattributes)) {
            $this->SAMLattributes = $this->sessionFetch(self::ATTRS_KEY);
        }
        return $this->SAMLattributes;
    }
}

?>
