<?php

if (!$loader = include __DIR__.'/../vendor/autoload.php') {
    $nl = PHP_SAPI === 'cli' ? PHP_EOL : '<br />';
    echo "$nl$nl";
    die('You must set up the project dependencies.'.$nl.
        'Run the following commands in '.dirname(__DIR__).':'.$nl.$nl.
        'curl -s http://getcomposer.org/installer | php'.$nl.
        'php composer.phar install'.$nl);
}

use Doctrine\Common\Annotations\AnnotationRegistry;

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->add('', __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs');
}

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

// Swiftmailer needs a special autoloader to allow
// the lazy loading of the init file (which is expensive)
require_once __DIR__.'/../vendor/swiftmailer/swiftmailer/lib/classes/Swift.php';
Swift::registerAutoload(__DIR__.'/../vendor/swiftmailer/swiftmailer/lib/swift_init.php');

// Use APC as autoloading to improve performance
//if (ini_get('apc.enabled')) {
    //require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/ClassLoader/ApcClassLoader.php';

    // Change 'sf2' by the prefix you want in order to prevent key conflict with an other application
    //$apcClassLoader = new Symfony\Component\ClassLoader\ApcClassLoader('sf2', $loader);
    //$apcClassLoader->register(true);
//}

