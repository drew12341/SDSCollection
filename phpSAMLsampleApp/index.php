<?php
date_default_timezone_set('America/New_York');
require_once("lib/bootstrap.php");
try {
	if (!PHPSAMLProcessor::self()->isAuthenticated()) {
		$authUrl = Config::getAuthUrl(); //taken from okta.config.xml
		$samlRequest = PHPSAMLProcessor::self()->createSAMLRequest();
		$relayState = Config::getBaseUrl();
		$redirUrl = $authUrl . "?SAMLRequest=" . urlencode(base64_encode($samlRequest)) .  "&RelayState=" . urlencode($relayState);
		header("Location: " . $redirUrl);
	} else {		
		echo "<center><h1>PHP SAML Sample Application</h1><br><br>";
		echo "Authenticated User: <b><font color=green>" . 
			PHPSAMLProcessor::self()->getAuthenticatedUserId() . "</font></b>"; 
		echo "<br><br>";
		echo "<a href=logout.php>Logout of SAML (Not Okta)</a>";		
		echo "</center>";
	}
} catch (Exception $e) {
    echo "ERROR:" . $e->getMessage();
} 
?>
