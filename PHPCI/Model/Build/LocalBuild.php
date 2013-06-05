<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Model\Build;

use PHPCI\Model\Build;
use PHPCI\Builder;
use Symfony\Component\Yaml\Parser as YamlParser;

/**
* Local Build Model
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Core
*/
class LocalBuild extends Build
{
    /**
    * Create a working copy by cloning, copying, or similar.
    */
    public function createWorkingCopy(Builder $builder, $buildPath)
    {
        $reference  = $this->getProject()->getReference();
        $reference  = substr($reference, -1) == '/' ? substr($reference, 0, -1) : $reference;
        $buildPath  = substr($buildPath, 0, -1);
        $yamlParser = new YamlParser();

        if (!is_file($reference . '/phpci.yml')) {
            $builder->logFailure('Project does not contain a phpci.yml file.');
            return false;
        }

        $yamlFile = file_get_contents($reference . '/phpci.yml');
        $builder->setConfigArray($yamlParser->parse($yamlFile));

        $buildSettings = $builder->getConfig('build_settings');

        if (isset($buildSettings['prefer_symlink']) && $buildSettings['prefer_symlink'] === true) {
            if (is_link($buildPath) && is_file($buildPath)) {
                unlink($buildPath);
            }

            $builder->log(sprintf('Symlinking: %s to %s', $reference, $buildPath));

            if (!symlink($reference, $buildPath)) {
                $builder->logFailure('Failed to symlink.');
                return false;
            }
        } else {
            $builder->executeCommand('cp -Rf "%s" "%s/"', $reference, $buildPath);
        }

        return true;
    }
}
