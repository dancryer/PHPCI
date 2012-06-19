<?php

if (!$loader = @include __DIR__.'/../vendor/autoload.php') {

    $message = <<< EOF
<p>You must set up the project dependencies by running the following commands:</p>
<pre>
    curl -s http://getcomposer.org/installer | php
    php composer.phar install
</pre>

EOF;

    if (PHP_SAPI === 'cli') {
        $message = strip_tags($message);
    }

    die($message);
}

use Doctrine\Common\Annotations\AnnotationRegistry;

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->add('', __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs');
}

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

// Use APC as autoloading to improve performance
/*
if (ini_get('apc.enabled')) {
    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/ClassLoader/ApcClassLoader.php';

    // Change 'sf2' by the prefix you want in order to prevent key conflict with another application
    $apcClassLoader = new Symfony\Component\ClassLoader\ApcClassLoader('sf2', $loader);
    $apcClassLoader->register(true);
}
*/
