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
* Remote Git Build Model
* @author       Nadir Dzhilkibaev <imam.sharif@gmail.com>
* @package      PHPCI
* @subpackage   Core
*/
class SubversionBuild extends Build
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
        $this->handleConfig($builder, $buildPath);
        
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
    * Use an HTTP-based git clone.
    */
    protected function cloneByHttp(Builder $builder, $cloneTo)
    {
        $cmd = 'svn export ';    
        
        $svn = $builder->getConfig('svn');
        if (!is_null($svn)) {
	    foreach ($svn as $key => $value)
		$cmd .= ' --' . $key . ' ' . $value . ' ';
        }
        
        $depth = $builder->getConfig('clone_depth');
        
        if (!is_null($depth)) {
            $cmd .= ' --depth ' . intval($depth) . ' ';
        }
        
        $cmd .= ' --non-interactive -q -r %s %s "%s"';

        $success = $builder->executeCommand($cmd, $this->getBranch(), $this->getCloneUrl(), $cloneTo);

        if ($success) {
            $success = $this->postCloneSetup($builder, $cloneTo);
        }

        return $success;
    }

    /**
    * Use an SSH-based git clone.
    */
    protected function cloneBySsh(Builder $builder, $cloneTo)
    {
        $keyFile = $this->writeSshKey($cloneTo);

        if (!IS_WIN) {
            $sshWrapper = $this->writeSshWrapper($cloneTo, $keyFile);
        }

        // Do the svn export:
        $cmd = 'svn export ';

        $svn = $builder->getConfig('svn');
        if (!is_null($svn)) {
	    foreach ($svn as $key => $value)
		$cmd .= ' --' . $key . ' ' . $value . ' ';
        }

        $depth = $builder->getConfig('clone_depth');

        if (!is_null($depth)) {
            $cmd .= ' -q --depth ' . intval($depth) . ' ';
        }

        $cmd .= ' -b %s %s "%s"';

        if (!IS_WIN) {
            $cmd = 'export SVN_SSH="'.$sshWrapper.'" && ' . $cmd;
        }

        $success = $builder->executeCommand($cmd, $this->getBranch(), $this->getCloneUrl(), $cloneTo);

        if ($success) {
            $success = $this->postCloneSetup($builder, $cloneTo);
        }

        // Remove the key file and git wrapper:
        unlink($keyFile);
        if (!IS_WIN) {
            unlink($sshWrapper);
        }

        return $success;
    }

    /**
     * Handle any post-clone tasks, like switching branches.
     * @param Builder $builder
     * @param $cloneTo
     * @return bool
     */
    protected function postCloneSetup(Builder $builder, $cloneTo)
    {
        $success = true;

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

        // Write the contents of this project's git key to the file:
        file_put_contents($keyFile, $this->getProject()->getSshPrivateKey());
        chmod($keyFile, 0600);

        // Return the filename:
        return $keyFile;
    }

    /**
     * Create an SSH wrapper script for Git to use, to disable host key checking, etc.
     * @param $cloneTo
     * @param $keyFile
     * @return string
     */
    protected function writeSshWrapper($cloneTo, $keyFile)
    {
        $path = dirname($cloneTo . '/temp');
        $wrapperFile = $path . '.sh';

        $sshFlags = '-o CheckHostIP=no -o IdentitiesOnly=yes -o StrictHostKeyChecking=no -o PasswordAuthentication=no';

        // Write out the wrapper script for this build:
        $script = <<<OUT
#!/bin/sh
ssh {$sshFlags} -o IdentityFile={$keyFile} $*

OUT;

        file_put_contents($wrapperFile, $script);
        shell_exec('chmod +x "'.$wrapperFile.'"');

        return $wrapperFile;
    }
}
