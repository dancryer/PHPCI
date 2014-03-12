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
* Remote Git Build Model
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Core
*/
class RemoteGitBuild extends Build
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
        $key = trim($this->getProject()->getGitKey());

        if (!empty($key)) {
            $success = $this->cloneBySsh($builder, $buildPath);
        } else {
            $success = $this->cloneByHttp($builder, $buildPath);
        }

        if (!$success) {
            $builder->logFailure('Failed to clone remote git repository.');
            return false;
        }

        if (!is_file($buildPath . 'phpci.yml')) {
            $builder->logFailure('Project does not contain a phpci.yml file.');
            return false;
        }

        $yamlFile = file_get_contents($buildPath . 'phpci.yml');
        $builder->setConfigArray($yamlParser->parse($yamlFile));

        return true;
    }

    /**
    * Use an HTTP-based git clone.
    */
    protected function cloneByHttp(Builder $builder, $cloneTo)
    {
        $success = $builder->executeCommand('git clone -b %s %s "%s"', $this->getBranch(), $this->getCloneUrl(), $cloneTo);

        if (!empty($commit) && $commit != 'Manual') {
            $cmd = 'cd "%s" && git checkout %s';
            if (IS_WIN) {
                $cmd = 'cd /d "%s" && git checkout %s';
            }
            $builder->executeCommand($cmd, $cloneTo, $this->getCommitId());
        }

        return $success;
    }

    /**
    * Use an SSH-based git clone.
    */
    protected function cloneBySsh(Builder $builder, $cloneTo)
    {
        // Copy the project's keyfile to disk:
        $keyPath = realpath($cloneTo);

        if ($keyPath === false) {
            $keyPath = dirname($cloneTo);
        }

        $keyFile = $keyPath . '.key';

        file_put_contents($keyFile, $this->getProject()->getGitKey());
        chmod($keyFile, 0600);

        // Use the key file to do an SSH clone:
        $cmd = 'eval `ssh-agent -s` && ssh-add "%s" && git clone -b %s %s "%s" && ssh-agent -k';
        $success = $builder->executeCommand($cmd, $keyFile, $this->getBranch(), $this->getCloneUrl(), $cloneTo);

        $commit = $this->getCommitId();

        if (!empty($commit) && $commit != 'Manual') {
            $cmd = 'cd "%s" && git checkout %s';
            if (IS_WIN) {
                $cmd = 'cd /d "%s" && git checkout %s';
            }
            $builder->executeCommand($cmd, $cloneTo, $this->getCommitId());
        }

        // Remove the key file:
        unlink($keyFile);

        return $success;
    }
}
