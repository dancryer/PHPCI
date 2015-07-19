<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\CommandExecutor;

use PHPCI\CommandExecutor\UnixCommandExecutor;
use PHPCI\CommandExecutor\WindowsCommandExecutor;
use PHPCI\Logging\BuildLogger;

/**
 * Construct an appropriate CommandExecutor instance.
 *
 * @author Mavimo <mavimo@gmail.com>
 */
class Factory
{
    /**
     * Create a CommandExecutor depending on available OS.
     *
     * Check UnixCommandExecutor and WindowsCommandExecutor.
     *
     * @return CommandExecutorInterface
     *
     * @internal
     */
    public static function createCommandExecutor(BuildLogger $buildLogger, $rootDir, $quiet = false, $verbose = false)
    {
        if (IS_WIN) {
            return new WindowsCommandExecutor($buildLogger, $rootDir, $quiet, $verbose);
        }

        return new UnixCommandExecutor($buildLogger, $rootDir, $quiet, $verbose);
    }
}
