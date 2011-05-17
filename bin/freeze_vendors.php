#!/usr/bin/env php
<?php

/*
 * This file is part of the Symfony Standard Edition.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$rootDir = dirname(__DIR__);
$vendorDir = $rootDir.'/vendor';
$version = trim(file_get_contents($rootDir.'/VERSION'));

$deps = array();
foreach (file(__DIR__.'/deps') as $line) {
    if (!trim($line)) {
        continue;
    }
    $parts = array_values(array_filter(explode(' ', trim($line))));
    if (3 !== count($parts)) {
        die(sprintf('The deps file is not valid (near "%s")', $line));
    }
    list($path, $name, $url) = $parts;

    ob_start();
    system('cd '.$vendorDir.'/'.$path.'/'.$name.'; git log -n 1 --format=%H');
    $deps[] = trim($name.' '.ob_get_clean());
}
file_put_contents($rootDir.'/bin/'.$version.'.deps', implode("\n", $deps));
