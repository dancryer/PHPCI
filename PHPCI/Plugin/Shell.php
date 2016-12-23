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
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;
use Psr\Log\LogLevel;

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

    /**
     * Standard Constructor
     *
     * $options['directory'] Output Directory. Default: %BUILDPATH%
     * $options['filename']  Phar Filename. Default: build.phar
     * $options['regexp']    Regular Expression Filename Capture. Default: /\.php$/
     * $options['stub']      Stub Content. No Default Value
     *
     * @param Builder $phpci
     * @param Build   $build
     * @param array   $options
     */
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
            throw new \Exception(Lang::get('shell_not_enabled'));
        }

        $success = true;

        foreach ($this->commands as $key => $command) {

            if(is_string($key) && is_array($command)) {
                /*
                 * shell:
                 *    master:
                 *       - "cd /www"
                 *       - "rm -f file.txt"
                 */
                $branch = $key;

                if($branch !== $this->build->getBranch()) {
                    $this->phpci->log(
                        "Shell requires $branch found ".$this->build->getBranch()." bypassing",
                        Loglevel::DEBUG
                    );
                    continue;
                }

                foreach ($command as $branchCommand) {
                    $this->phpci->log(
                        "Executing $branchCommand",
                        Loglevel::DEBUG
                    );
                    $branchCommand = $this->phpci->interpolate($branchCommand);

                    if(!$this->phpci->executeCommand($branchCommand))
                    {
                        $success = FALSE;
                    }
                }
            }
            elseif(is_int($key) && is_string($command))
            {
                /*
                 * shell:
                 *     - "cd /www"
                 *     - "rm -f file.txt"
                 */
                $command = $this->phpci->interpolate($command);

                if(!$this->phpci->executeCommand($command))
                {
                    $success = FALSE;
                }
            }
        }

        return $success;
    }
}
