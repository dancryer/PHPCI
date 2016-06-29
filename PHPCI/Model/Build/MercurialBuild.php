<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Model\Build;

use PHPCI\Model\Build;
use PHPCI\Builder;

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
        $key = trim($this->getProject()->getSshPublicKey());

        if (!empty($key) && strpos($this->getProject()->getReference(), 'ssh') > -1) {
            $success = $this->cloneBySsh($builder, $buildPath);
        } else {
            $success = $this->cloneByHttp($builder, $buildPath);
        }

        if (!$success) {
            $builder->logFailure('Failed to clone remote hg repository.');
            return false;
        }

        return $this->handleConfig($builder, $buildPath);
    }

    /**
     * Use a HTTP-based Mercurial clone.
     */
    protected function cloneByHttp(Builder $builder, $cloneTo)
    {
        return $builder->executeCommand('hg clone %s "%s" -r %s', $this->getCloneUrl(), $cloneTo, $this->getBranch());
    }

    /**
     * Create an SSH key file on disk for this build.
     * @param $cloneTo
     * @return string
     */
    protected function writeSshKey($cloneTo)
    {
        $keyPath = dirname($cloneTo . '/temp');
        $keyFile = $keyPath . '.key';

        // Write the contents of this project's hg key to the file:
        file_put_contents($keyFile, $this->getProject()->getSshPrivateKey());
        chmod($keyFile, 0600);

        // Return the filename:
        return $keyFile;
    }

    /**
     * Use an SSH-based Mercurial clone.
     */
    protected function cloneBySsh(Builder $builder, $cloneTo)
    {
        $keyFile = $this->writeSshKey($cloneTo);

        // Do the hg clone:
        $cmd = 'hg clone --ssh "ssh -i '.$keyFile.'" %s "%s" -r %s';
        $success = $builder->executeCommand($cmd, $this->getCloneUrl(), $cloneTo, $this->getBranch());

        if ($success) {
            $success = $this->postCloneSetup($builder, $cloneTo);
        }

        // Remove the key file:
        unlink($keyFile);
        return $success;
    }

    /**
     * Handle post-clone tasks (switching branch, etc.)
     * @param Builder $builder
     * @param $cloneTo
     * @return bool
     */
    protected function postCloneSetup(Builder $builder, $cloneTo)
    {
        $success = true;
        $commit = $this->getCommitId();

        // Allow switching to a specific branch:
        if (!empty($commit) && $commit != 'Manual') {
            $cmd = 'cd "%s" && hg checkout %s';
            $success = $builder->executeCommand($cmd, $cloneTo, $this->getBranch());
        }

        return $success;
    }
}
