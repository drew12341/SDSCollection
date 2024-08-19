<?php
    require_once(dirname(__FILE__) . "/lib/bootstrap.php");
    PHPSAMLProcessor::self()->logout();
    header("Location: " . Config::getLogoutUrl());
?>
