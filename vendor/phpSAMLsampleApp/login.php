<?php
date_default_timezone_set('Australia/ACT');
require_once(dirname(__FILE__) . "/lib/bootstrap.php");

if (!PHPSAMLProcessor::self()->isAuthenticated()) {
    PHPSAMLProcessor::self()->init();
} 
$relayState = @trim($_REQUEST["RelayState"]);
header( 'Location: '. $relayState)

?>
