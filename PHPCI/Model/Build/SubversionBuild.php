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
 * Remote Subversion Build Model
 * @author       Nadir Dzhilkibaev <imam.sharif@gmail.com>
 * @package      PHPCI
 * @subpackage   Core
 */
class SubversionBuild extends Build
{
    protected $svnCommand = 'svn export -q --non-interactive ';

    /**
     * Get the URL to be used to clone this remote repository.
     */
    protected function getCloneUrl()
    {
        $url = $this->getProject()->getReference();

        if (substr($url, -1) != '/') {
            $url .= '/';
        }

        $branch = $this->getBranch();

        if (empty($branch) || $branch == 'trunk') {
            $url .= 'trunk';
        } else {
            $url .= 'branches/' . $branch;
        }

        return $url;
    }

    /**
     * @param Builder $builder
     *
     * @return void
     */
    protected function extendSvnCommandFromConfig(Builder $builder)
    {
        $cmd = $this->svnCommand;

        $svn = $builder->getConfig('svn');
        if ($svn) {
            foreach ($svn as $key => $value) {
                $cmd .= " --$key $value ";
            }
        }

        $depth = $builder->getConfig('clone_depth');

        if (!is_null($depth)) {
            $cmd .= ' --depth ' . intval($depth) . ' ';
        }

        $this->svnCommand = $cmd;
    }

    /**
     * Create a working copy by cloning, copying, or similar.
     */
    public function createWorkingCopy(Builder $builder, $buildPath)
    {
        $this->handleConfig($builder, $buildPath);

        $this->extendSvnCommandFromConfig($builder);

        $key = trim($this->getProject()->getSshPrivateKey());

        if (!empty($key)) {
            $success = $this->cloneBySsh($builder, $buildPath);
        } else {
            $success = $this->cloneByHttp($builder, $buildPath);
        }

        if (!$success) {
            $builder->logFailure('Failed to export remote subversion repository.');
            return false;
        }

        return $this->handleConfig($builder, $buildPath);
    }

    /**
     * Use an HTTP-based svn export.
     */
    protected function cloneByHttp(Builder $builder, $cloneTo)
    {
        $cmd = $this->svnCommand;

        if ($this->getCommitId() != 'Manual') {
            $cmd .= ' -r %s %s "%s"';
            $success = $builder->executeCommand($cmd, $this->getCommitId(), $this->getCloneUrl(), $cloneTo);
        } else {
            $cmd .= ' %s "%s"';
            $success = $builder->executeCommand($cmd, $this->getCloneUrl(), $cloneTo);
        }

        return $success;
    }

    /**
     * Use an SSH-based svn export.
     */
    protected function cloneBySsh(Builder $builder, $cloneTo)
    {
        $cmd = $this->svnCommand . ' %s "%s"';

        if (!IS_WIN) {
            $keyFile    = $this->writeSshKey($cloneTo);
            $sshWrapper = $this->writeSshWrapper($cloneTo, $keyFile);
            $cmd        = 'export SVN_SSH="' . $sshWrapper . '" && ' . $cmd;
        }

        $success = $builder->executeCommand($cmd, $this->getCloneUrl(), $cloneTo);

        if (!IS_WIN) {
            // Remove the key file and svn wrapper:
            unlink($keyFile);
            unlink($sshWrapper);
        }

        return $success;
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

        // Write the contents of this project's svn key to the file:
        file_put_contents($keyFile, $this->getProject()->getSshPrivateKey());
        chmod($keyFile, 0600);

        // Return the filename:
        return $keyFile;
    }

    /**
     * Create an SSH wrapper script for Svn to use, to disable host key checking, etc.
     * @param $cloneTo
     * @param $keyFile
     * @return string
     */
    protected function writeSshWrapper($cloneTo, $keyFile)
    {
        $path        = dirname($cloneTo . '/temp');
        $wrapperFile = $path . '.sh';

        $sshFlags = '-o CheckHostIP=no -o IdentitiesOnly=yes -o StrictHostKeyChecking=no -o PasswordAuthentication=no';

        // Write out the wrapper script for this build:
        $script = <<<OUT
#!/bin/sh
ssh {$sshFlags} -o IdentityFile={$keyFile} $*

OUT;

        file_put_contents($wrapperFile, $script);
        shell_exec('chmod +x "' . $wrapperFile . '"');

        return $wrapperFile;
    }
}
