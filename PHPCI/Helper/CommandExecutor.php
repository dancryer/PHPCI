<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Helper;

interface CommandExecutor
{
    /**
     * Executes shell commands. Accepts multiple arguments the first
     * is the template and everything else is inserted in. c.f. sprintf
     * @return bool Indicates success
     */
    public function executeCommand();

    /**
     * Returns the output from the last command run.
     */
    public function getLastOutput();

    /**
     * Find a binary required by a plugin.
     * @param string $binary
     * @param bool $quiet Returns null instead of throwing an execption.
     *
     * @return null|string
     *
     * @throws \Exception when no binary has been found and $quiet is false.
     */
    public function findBinary($binary, $quiet = false);

    /**
     * Set the buildPath property.
     * @param string $path
     */
    public function setBuildPath($path);
}
