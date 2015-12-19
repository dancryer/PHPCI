<?php

/**
 * PHPCI - Continuous Integration for PHP. Plugin for using Symfony2 commands.
 *
 * @package    PHPCI\Plugin
 * @author     Alexander Gansky <a.gansky@mindteam.com.ua>
 * @license    https://github.com/mindteam/phpci-symfony2-plugin/blob/master/LICENSE
 * @link       http://mindteam.com.ua
 */

namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;
use Symfony\Component\Yaml\Parser as YamlParser;
use PHPCI\Plugin as BaseInterface;

/**
 * Plugin for Symfony2 commands
 */
class SymfonyCommands implements BaseInterface
{

    protected $directory;
    protected $phpci;
    protected $build;
    protected $commandList = array();

    /**
     * Set up the plugin, configure options, etc.
     *
     * @param Builder $phpci
     * @param Build $build
     * @param array $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;
        $this->directory = $phpci->buildPath;
        if (isset($options['commands'])) {
            $this->commandList = $options['commands'];
        }
    }

    /**
     * Executes Symfony2 commands
     *
     * @return boolean plugin work status
     */
    public function execute()
    {
        $success = true;
        foreach ($this->commandList as $command) {
            if (!$this->runSingleCommand($command)) {
                $success = false;
                break;
            }
        }
        return $success;
    }

    /**
     * Run one command
     *
     * @param string $command command for cymfony
     *
     * @return boolean
     */
    public function runSingleCommand($command)
    {
        $cmd = 'php ' . $this->directory . 'app/console ';

        return $this->phpci->executeCommand($cmd . $command, $this->directory);
    }

}
