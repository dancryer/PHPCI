<?php

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
     * @return null|string
     */
    public function findBinary($binary);
}
