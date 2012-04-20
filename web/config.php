<?php

if (!isset($_SERVER['HTTP_HOST'])) {
    exit('This script cannot be run from the CLI. Run it from a browser.');
}

if (!in_array(@$_SERVER['REMOTE_ADDR'], array(
    '127.0.0.1',
    '::1',
))) {
    header('HTTP/1.0 403 Forbidden');
    exit('This script is only accessible from localhost.');
}

require_once dirname(__FILE__).'/../app/SymfonyRequirements.php';

$symfonyRequirements = new SymfonyRequirements();

$majorProblems = $symfonyRequirements->getFailedRequirements();
$minorProblems = $symfonyRequirements->getFailedRecommendations();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="bundles/sensiodistribution/webconfigurator/css/install.css" media="all" />
        <title>Symfony Configuration</title>
    </head>
    <body>
        <div id="symfony-wrapper">
            <div id="symfony-content">
                <div class="symfony-blocks-install">
                    <div class="symfony-block-logo">
                        <img src="bundles/sensiodistribution/webconfigurator/images/logo-big.gif" alt="Symfony logo" />
                    </div>

                    <div class="symfony-block-content">
                        <h1>Welcome!</h1>
                        <p>Welcome to your new Symfony project.</p>
                        <p>
                            This script will guide you through the basic configuration of your project.
                            You can also do the same by editing the ‘<strong>app/config/parameters.yml</strong>’ file directly.
                        </p>

                        <?php if (count($majorProblems)): ?>
                            <h2>Major problems</h2>
                            <p>Major problems have been detected and <strong>must</strong> be fixed before continuing:</p>
                            <ol>
                                <?php foreach ($majorProblems as $problem): ?>
                                    <li><?php echo $problem->getHelpHtml() ?></li>
                                <?php endforeach; ?>
                            </ol>
                        <?php endif; ?>

                        <?php if (count($minorProblems)): ?>
                            <h2>Recommendations</h2>
                            <p>
                                <?php if (count($majorProblems)): ?>Additionally, to<?php else: ?>To<?php endif; ?> enhance your Symfony experience,
                                it’s recommended that you fix the following:
                            </p>
                            <ol>
                                <?php foreach ($minorProblems as $problem): ?>
                                    <li><?php echo $problem->getHelpHtml() ?></li>
                                <?php endforeach; ?>
                            </ol>
                        <?php endif; ?>

                        <?php if ($symfonyRequirements->hasPhpIniConfigIssue()): ?>
                            <p id="phpini">*
                                <?php if ($symfonyRequirements->getPhpIniConfigPath()): ?>
                                    Changes to the <strong>php.ini</strong> file must be done in "<strong><?php echo $symfonyRequirements->getPhpIniConfigPath() ?></strong>".
                                <?php else: ?>
                                    To change settings, create a "<strong>php.ini</strong>".
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>

                        <ul class="symfony-install-continue">
                            <?php if (!count($majorProblems)): ?>
                                <li><a href="app_dev.php/_configurator/">Configure your Symfony Application online</a></li>
                                <li><a href="app_dev.php/">Bypass configuration and go to the Welcome page</a></li>
                            <?php endif; ?>
                            <li><a href="config.php">Re-check configuration</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="version">Symfony Standard Edition</div>
    </body>
</html>
