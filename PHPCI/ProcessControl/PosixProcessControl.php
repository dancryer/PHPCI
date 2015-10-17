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
 * Control process using the POSIX extension.
 *
 * @author Adirelle <adirelle@gmail.com>
 */
class PosixProcessControl implements ProcessControlInterface
{
    /**
     *
     * @param int $pid
     * @return bool
     */
    public function isRunning($pid)
    {
        // Signal "0" is not sent to the process, but posix_kill checks the process anyway;
        return posix_kill($pid, 0);
    }

    /**
     * Sends a TERMINATE or KILL signal to the process using posix_kill.
     *
     * @param int $pid
     * @param bool $forcefully Whether to send TERMINATE (false) or KILL (true).
     */
    public function kill($pid, $forcefully = false)
    {
        posix_kill($pid, $forcefully ? 9 : 15);
    }

    /**
     * Check whether this posix_kill is available.
     *
     * @return bool
     *
     * @internal
     */
    public static function isAvailable()
    {
        return function_exists('posix_kill');
    }
}
