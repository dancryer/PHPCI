<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Model\Build;

use b8\Config;
use PHPCI\Model\Build;
use PHPCI\Builder;

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
        $cloneArgs = '--branch "'.$this->getBranch().'"';

        $depth = $builder->getConfig('clone_depth');
        if (!is_null($depth)) {
            $cloneArgs .= ' --depth ' . intval($depth);
        }

        $success = true;

        // Create and/or update a mirror repository if phpci.git.mirrors if defined
        $mirrorPath = $this->getMirrorPath();
        if (null !== $mirrorPath) {
            $success = $this->manageMirror($builder, $mirrorPath);
            $cloneArgs .= ' --reference="'.$mirrorPath.'"';
        }

        if ($success
            && $this->cloneTo($builder, $buildPath, $cloneArgs)
            && $this->postCloneSetup($builder, $buildPath)
        ) {
            return $this->handleConfig($builder, $buildPath);
        }

        $builder->logFailure('Failed to clone remote git repository.');
        return false;
    }

    /** Calculate the path to a repository mirror.
     *
     * @return string|null The path or null if mirrors aren't enabled.
     */
    public function getMirrorPath()
    {
        $mirrorRootPath = Config::getInstance()->get('phpci.git.mirrors');
        if (!$mirrorRootPath) {
            return null;
        }
        return $mirrorRootPath.'/'.$this->getProjectId();
    }

    /**
     * Remove the repository mirror.
     */
    public function removeMirror()
    {
        $mirrorPath = $this->getMirrorPath();
        if ($mirrorPath && is_dir($mirrorPath)) {
            $cmd = sprintf(
                IS_WIN ? 'rmdir /S /Q "%s"' : 'rm -rf "%s"',
                $mirrorPath
            );
            exec($cmd);
        }
    }

    /** Create and/or update a persistent mirror of the remote repository.
     *
     * @param Builder $builder
     * @param string $mirrorPath
     *
     * @return bool
     */
    protected function manageMirror(Builder $builder, $mirrorPath)
    {
        if (!is_dir($mirrorPath)) {
            // First run: create the mirror
            return $this->cloneTo($builder, $mirrorPath, '--mirror');
        }

        // Update the mirror
        return $builder->executeCommand('git --git-dir="%s" --bare remote update --prune', $mirrorPath);
    }

    /** Clone a remote repository into a local directory.
     *
     * @param Builder $builder
     * @param string $cloneTo
     * @param string $args Additional arguments.
     *
     * @return bool
     */
    protected function cloneTo(Builder $builder, $cloneTo, $args)
    {
        if (preg_match('/^((f|ht)tps?|git|file):/', $this->getCloneUrl())) {
            // Not SSH URL
            return $builder->executeCommand('git clone %s "%s" "%s"', $args, $this->getCloneUrl(), $cloneTo);
        }

        $cmd = 'git clone %s "%s" "%s"';

        // Create the keyfile and a SSH wrapper
        $keyFile = $this->writeSshKey($cloneTo);
        if (!IS_WIN) {
            $gitSshWrapper = $this->writeSshWrapper($cloneTo, $keyFile);
            $cmd = 'export GIT_SSH="'.$gitSshWrapper.'" && ' . $cmd;
        }

        $success = $builder->executeCommand($cmd, $args, $this->getCloneUrl(), $cloneTo);

        // Remove the key file and git wrapper:
        unlink($keyFile);
        if (!IS_WIN) {
            unlink($gitSshWrapper);
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
        $commit = $this->getCommitId();

        if (!empty($commit) && $commit != 'Manual') {
            $cmd = 'cd "%s"';

            if (IS_WIN) {
                $cmd = 'cd /d "%s"';
            }

            $cmd .= ' && git checkout %s --quiet';

            $success = $builder->executeCommand($cmd, $cloneTo, $this->getCommitId());
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
