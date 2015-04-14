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

        // If there's a /config file in the reference directory, it is probably a bare repository
        // which we'll extract into our build path directly.
        if (is_file($reference.'/config') && $this->handleBareRepository($builder, $reference, $buildPath) === true) {
            return $this->handleConfig($builder, $buildPath) !== false;
        }

        $configHandled = $this->handleConfig($builder, $reference);

        if ($configHandled === false) {
            return false;
        }

        $buildSettings = $builder->getConfig('build_settings');

        if (isset($buildSettings['prefer_symlink']) && $buildSettings['prefer_symlink'] === true) {
            return $this->handleSymlink($builder, $reference, $buildPath);
        } else {
            $cmd = 'cp -Rf "%s" "%s/"';
            if (IS_WIN) {
                $cmd = 'xcopy /E /Y "%s" "%s/*"';
            }
            $builder->executeCommand($cmd, $reference, $buildPath);
        }

        return true;
    }

    /**
     * Check if this is a "bare" git repository, and if so, unarchive it.
     * @param Builder $builder
     * @param $reference
     * @param $buildPath
     * @return bool
     */
    protected function handleBareRepository(Builder $builder, $reference, $buildPath)
    {
        $gitConfig = parse_ini_file($reference.'/config', true);

        // If it is indeed a bare repository, then extract it into our build path:
        if ($gitConfig['core']['bare']) {
            $cmd = 'mkdir %2$s; git --git-dir="%1$s" archive %3$s | tar -x -C "%2$s"';
            $builder->executeCommand($cmd, $reference, $buildPath, $this->getBranch());
            return true;
        }

        return false;
    }

    /**
     * Create a symlink if required.
     * @param Builder $builder
     * @param $reference
     * @param $buildPath
     * @return bool
     */
    protected function handleSymlink(Builder $builder, $reference, $buildPath)
    {
        if (is_link($buildPath) && is_file($buildPath)) {
            unlink($buildPath);
        }

        $builder->log(sprintf('Symlinking: %s to %s', $reference, $buildPath));

        if (!symlink($reference, $buildPath)) {
            $builder->logFailure('Failed to symlink.');
            return false;
        }

        return true;
    }
}
