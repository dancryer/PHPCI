<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\ProcessControl;

/**
 * Control processes using the "ps" and "kill" commands.
 *
 * @author Adirelle <adirelle@gmail.com>
 */
class UnixProcessControl implements ProcessControlInterface
{
    /**
     * Check process using the "ps" command.
     *
     * @param int $pid
     * @return boolean
     */
    public function isRunning($pid)
    {
        $output = $exitCode = null;
        exec(sprintf("ps %d", $pid), $output, $exitCode);
        return $exitCode === 0;
    }

    /**
     * Sends a signal using the "kill" command.
     *
     * @param int $pid
     * @param bool $forcefully
     */
    public function kill($pid, $forcefully = false)
    {
        exec(sprintf("kill -%d %d", $forcefully ? 9 : 15, $pid));
    }

    /**
     * Check whether the commands "ps" and "kill" are available.
     *
     * @return bool
     *
     * @internal
     */
    public static function isAvailable()
    {
        return DIRECTORY_SEPARATOR === '/' && exec("which ps") && exec("which kill");
    }
}
