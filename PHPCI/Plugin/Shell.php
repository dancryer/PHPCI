<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
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
    protected $args;
    protected $phpci;

    /**
     * @var string $command The command to be executed
     */
    protected $command;

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci        = $phpci;

        if (isset($options['command'])) {
            $command       = $options['command'];
            $command       = str_replace("%buildpath%", $this->phpci->buildPath, $command);
            $this->command = $command;
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
        
        $success = $this->phpci->executeCommand($this->command);
        
        return $success;
    }
}
