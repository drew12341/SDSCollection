<?php
require_once((dirname(__FILE__)) . "/lib/bootstrap.php");
require_once(dirname(__FILE__) . "/web/header.php");
?>
<style type="text/css">
    .page .main {
        padding:0;
        margin:0;
    }
</style>
<center><div class="section">
    Configuration
</div></center>
<div class="section_contents">
    <div class="clearfix row">
        <div class="label">Issuer</div>
        <div class="value"><?php echo htmlspecialchars(Config::getIssuer()); ?></div>
    </div>
    <div class="clearfix row">
        <div class="label">AuthenticationUrl</div>
        <div class="value"><?php echo htmlspecialchars(Config::getAuthUrl()); ?></div>
    </div>
    <div class="clearfix row">
        <div class="label">Certificate</div>
        <div class="value"><?php echo nl2br(trim(htmlspecialchars(Config::getCert()))); ?></div>
    </div>
</div>

<center><div class="section">
    SAML request
</div></center>
<div class="section_contents">
    <div class="clearfix row">
        <div class="label">AuthenticationUrl</div>
        <div class="value"><?php echo htmlspecialchars(Config::getAuthUrl()); ?></div>
    </div>
    <div class="clearfix row">
        <div class="label">SAMLRequest</div>
        <div class="value">
            <?php
                $out = str_replace(">", ">\n", PHPSAMLProcessor::self()->createSAMLRequest());
                $out = str_replace("\n\n", "\n", $out);
                echo nl2br(htmlspecialchars($out));
            ?>
        </div>
    </div>
    <div class="clearfix row">
        <div class="label">RelayState</div>
        <div class="value"><?php echo Config::getRelayStateURL(); ?></div>
    </div>
    <div class="clearfix row">
        <div class="label">Redirection URL</div>
        <div class="value">
            <?php
                $authUrl = Config::getAuthUrl();
                $samlRequest = PHPSAMLProcessor::self()->createSAMLRequest();
                $relayState = Config::getRelayStateURL();
                $redirUrl = $authUrl . "?SAMLRequest=" . urlencode(base64_encode($samlRequest)) . "&RelayState=" . urlencode($relayState);
                echo '<a href="' . $redirUrl . '">link</a>';
            ?>
        </div>
    </div>    
    
    <center><div class="section">
        SAML response
    </div></center>
    <div class="section_contents">
        <?php
            $loginUrl = Config::getBaseUrl() . "/login.php?RelayState=" . urlencode(Config::getBaseUrl() . "/diag.php");
        ?>
        <iframe src="<?php echo $loginUrl; ?>" width="100%" height="100%" style="border:0; overflow:auto;" scrolling="auto">
            Your browser does not support iframes.
        </iframe>
    </div>
</div>
<?php include_once(dirname(__FILE__) . "web/footer.php"); ?>
