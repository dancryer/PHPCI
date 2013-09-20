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
* Mercurial Build Model
* @author       Pavel Gopanenko <pavelgopanenko@gmail.com>
* @package      PHPCI
* @subpackage   Core
*/
class MercurialBuild extends Build
{
    /**
    * Get the URL to be used to clone this remote repository.
    */
    protected function getCloneUrl()
    {
        return $this->getProject()->getReference();
    }

    /**
    * Create a working copy by cloning, copying, or similar.
    */
    public function createWorkingCopy(Builder $builder, $buildPath)
    {
        $yamlParser = new YamlParser();

        $this->cloneByHttp($builder, $buildPath);

        if (!is_file($buildPath . 'phpci.yml')) {
            $builder->logFailure('Project does not contain a phpci.yml file.');
            return false;
        }

        $yamlFile = file_get_contents($buildPath . 'phpci.yml');
        $builder->setConfigArray($yamlParser->parse($yamlFile));

        return true;
    }

    /**
    * Use an mercurial clone.
    */
    protected function cloneByHttp(Builder $builder, $to)
    {
        return $builder->executeCommand('hg clone %s "%s" -r %s', $this->getCloneUrl(), $to, $this->getBranch());
    }
}
