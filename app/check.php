<?php

require_once __DIR__.'/SymfonyRequirements.php';

$symfonyRequirements = new SymfonyRequirements();

$iniPath = $symfonyRequirements->getPhpIniConfigPath() ?: 'WARNING: not using a php.ini file';

echo "********************************\n";
echo "*                              *\n";
echo "*  Symfony requirements check  *\n";
echo "*                              *\n";
echo "********************************\n\n";

echo sprintf("Configuration file used by PHP: %s\n\n", $iniPath);

echo "** ATTENTION **\n";
echo "*  The PHP CLI can use a different php.ini file\n";
echo "*  than the one used with your web server.\n";
if ('\\' == DIRECTORY_SEPARATOR) {
    echo "*  (especially on the Windows platform)\n";
}
echo "*  If this is the case, please ALSO launch this\n";
echo "*  utility from your web server.\n";

echo_title('Mandatory requirements');

foreach ($symfonyRequirements->getRequirements() as $req) {
    echo_requirement($req);
}

echo_title('Optional recommendations');

foreach ($symfonyRequirements->getRecommendations() as $req) {
    echo_requirement($req);
}

/**
 * Prints a Requirement instance
 */
function echo_requirement(Requirement $requirement)
{
    $result = $requirement->isFulfilled() ? 'OK' : ($requirement->isOptional() ? 'WARNING' : 'ERROR');
    echo ' ' . str_pad($result, 9);
    echo $requirement->getTestMessage() . "\n";

    if (!$requirement->isFulfilled()) {
        echo sprintf("          %s\n\n", $requirement->getHelpText());
    }
}

function echo_title($title)
{
    echo "\n** $title **\n\n";
}
