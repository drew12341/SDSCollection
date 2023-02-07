<?php
require_once((dirname(__FILE__)) . "/lib/bootstrap.php");

PHPSAMLProcessor::self()->init();

include_once(dirname(__FILE__) . "/web/header.php");
echo file_get_contents(dirname(__FILE__) . "/web/doc.htm");
include_once(dirname(__FILE__) . "/web/footer.php");
?>
