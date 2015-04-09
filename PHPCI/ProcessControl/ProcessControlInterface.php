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
 * A stateless service to check and kill system processes.
 *
 * @author Adirelle <adirelle@gmail.com>
 */
interface ProcessControlInterface
{
    /** Checks if a process exists.
     *
     * @param int $pid The process identifier.
     *
     * @return boolean true is the process is running, else false.
     */
    public function isRunning($pid);

    /** Terminate a running process.
     *
     * @param int $pid The process identifier.
     * @param bool $forcefully Whether to gently (false) or forcefully (true) terminate the process.
     */
    public function kill($pid, $forcefully = false);
}
