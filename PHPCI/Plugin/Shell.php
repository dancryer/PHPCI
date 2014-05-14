<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;

/**
 * Shell Plugin - Allows execute shell commands.
 * @author       Kinn Coelho Julião <kinncj@gmail.com>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Shell implements \PHPCI\Plugin
{
    /**
     * @var \PHPCI\Builder
     */
    protected $phpci;

    /**
     * @var \PHPCI\Model\Build
     */
    protected $build;

    protected $args;

    /**
     * @var string[] $commands The commands to be executed
     */
    protected $commands = array();

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;

        if (isset($options['command'])) {
            // Keeping this for backwards compatibility, new projects should use interpolation vars.
            $options['command'] = str_replace("%buildpath%", $this->phpci->buildPath, $options['command']);
            $this->commands = array($options['command']);
            return;
        }

        /*
         * Support the new syntax:
         *
         * shell:
         *     - "cd /www"
         *     - "rm -f file.txt"
         */
        if (is_array($options)) {
            $this->commands = $options;
        }
    }

    /**
     * Runs the shell command.
     */
    public function execute()
    {
        if (!defined('ENABLE_SHELL_PLUGIN') || !ENABLE_SHELL_PLUGIN) {
            throw new \Exception('The shell plugin is not enabled.');
        }

        $success = true;

        foreach ($this->commands as $command) {
            $command = $this->phpci->interpolate($command);

            if (!$this->phpci->executeCommand($command)) {
                $success = false;
            }
        }

        return $success;
    }
}
