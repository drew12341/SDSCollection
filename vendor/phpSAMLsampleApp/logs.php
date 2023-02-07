<?php
require_once((dirname(__FILE__)) . "/lib/bootstrap.php");

PHPSAMLProcessor::self()->init();

include_once(dirname(__FILE__) . "/web/header.php");

if (isset($_GET["act"])) {
    switch($_GET["act"]) {
        case "clear":
            PHPSAMLProcessor::self()->clearLogs();
            PHPSAMLProcessor::self()->redirect("logs.php");
        break;
    }
}

echo '<h2>OKTA Errors Logs:</h2> ' .
    '<h3><a onclick="return confirm(\'Are you sure? This action cannot be revoked\');" href="?act=clear">Clear logs</a></h3>' .
    '<textarea spellcheck="false" readonly="readonly" style="width:100%; height:800px; overflow:auto;">' .
        PHPSAMLProcessor::self()->getLogs() . 
    '</textarea>';
    
include_once(dirname(__FILE__) . "/web/footer.php");
?>
