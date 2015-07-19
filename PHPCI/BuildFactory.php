<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2014, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         https://www.phptesting.org/
*/

namespace PHPCI;

use b8\Store\Factory;
use PHPCI\Model\Build;
use PHPCI\Store\BuildStore;

/**
* PHPCI Build Factory - Takes in a generic "Build" and returns a type-specific build model.
* @author   Dan Cryer <dan@block8.co.uk>
*/
class BuildFactory
{
    /**
     * @var BuildStore
     */
    protected $buildStore;

    public function __construct(BuildStore $buildStore)
    {
        $this->buildStore = $buildStore;
    }

    /**
     * @param $buildId
     * @return Build
     * @throws \Exception
     */
    public function getBuildById($buildId)
    {
        $build = $this->buildStore->getById($buildId);

        if (empty($build)) {
            throw new \Exception('Build ID ' . $buildId . ' does not exist.');
        }

        return $this->getBuild($build);
    }

    /**
    * Takes a generic build and returns a type-specific build model.
    * @param Build $base The build from which to get a more specific build type.
    * @return Build
    */
    public function getBuild(Build $base)
    {
        switch($base->getProject()->getType())
        {
            case 'remote':
                $type = 'RemoteGitBuild';
                break;
            case 'local':
                $type = 'LocalBuild';
                break;
            case 'github':
                $type = 'GithubBuild';
                break;
            case 'bitbucket':
                $type = 'BitbucketBuild';
                break;
            case 'gitlab':
                $type = 'GitlabBuild';
                break;
            case 'hg':
                $type = 'MercurialBuild';
                break;
            case 'svn':
                $type = 'SubversionBuild';
                break;
        }

        $type = '\\PHPCI\\Model\\Build\\' . $type;

        return new $type($base->getDataArray());
    }
}
