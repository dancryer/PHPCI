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
     * Executes shell commands.
     *
     * If $args contains more than one value, the first value is issued as a template and formatted using sprintf
     * with the remaining arguments.
     *
     * @param array $args Command.
     *
     * @return bool Indicates success
     */
    public function executeCommand(array $args = array());

    /**
     * Returns the output from the last command run.
     */
    public function getLastOutput();

    /**
     * Find a binary required by a plugin.
     * @param string $binary
     * @return null|string
     */
    public function findBinary($binary);

    /**
     * Set the buildPath property.
     * @param string $path
     */
    public function setBuildPath($path);
}
