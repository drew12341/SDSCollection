<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Demo PHP SAML Application</title>
        <link rel="stylesheet" type="text/css" href="web/styles.css" />
    </head>
    <body>
    <div class="page">
        <div class="header">
            <div class="title">
                <h1>Demo PHP Application</h1>
            </div>
            <div class="loginDisplay">
                <?php if (PHPSAMLProcessor::self()->isAuthenticated()): ?>
                Welcome <span class="bold">
                    <span id="ctl00_HeadLoginView_HeadLoginName"><?php echo PHPSAMLProcessor::self()->getAuthenticatedUserId(); ?></span>
                </span>! [ <a href="<?php echo Config::getLogoutUrl(); ?>">logout</a> ]
                <?php endif; ?>
            </div>
            <div class="clear hideSkiplink">
                <div id="ctl00_NavigationMenu" class="menu_links">
                    <a class="ctl00_NavigationMenu_1" href="">Home</a> |
                    <a class="ctl00_NavigationMenu_1" href="documentation.php">Documentation</a> |
                    <a class="ctl00_NavigationMenu_1" href="diag.php">Diagnostic</a> |
                    <a class="ctl00_NavigationMenu_1" href="logs.php">Logs</a>
                </div>
            </div>
        </div>
        <div class="main">
