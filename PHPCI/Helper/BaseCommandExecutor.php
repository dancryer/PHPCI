<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 *
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Helper;

use \PHPCI\Logging\BuildLogger;
use Psr\Log\LogLevel;
use PHPCI\Helper\Lang;

/**
 * Handles running system commands with variables.
 * @package PHPCI\Helper
 */
abstract class BaseCommandExecutor implements CommandExecutor
{
    /**
     * @var \PHPCI\Logging\BuildLogger
     */
    protected $logger;

    /**
     * @var bool
     */
    protected $quiet;

    /**
     * @var bool
     */
    protected $verbose;

    /**
     * @var string[]
     */
    protected $lastOutput;

    /**
     * @var string
     */
    protected $lastError;

    /**
     * @var bool
     */
    public $logExecOutput = true;

    /**
     * The path which findBinary will look in.
     *
     * @var string
     */
    protected $rootDir;

    /**
     * Current build path
     *
     * @var string
     */
    protected $buildPath;

    /**
     * @var Environment
     */
    protected $environment;

    /**
     * @param BuildLogger $logger
     * @param string      $rootDir
     * @param Environment $environment
     * @param bool        $quiet
     * @param bool        $verbose
     */
    public function __construct(
        BuildLogger $logger,
        $rootDir,
        Environment $environment = null,
        &$quiet = false,
        &$verbose = false
    ) {
        $this->logger = $logger;
        $this->quiet = $quiet;
        $this->verbose = $verbose;
        $this->lastOutput = array();
        $this->rootDir = $rootDir;
        $this->environment = $environment ? $environment : new Environment();
    }

    /**
     * Executes shell commands.
     *
     * @param array $args
     *
     * @return bool Indicates success
     */
    public function executeCommand(array $args = array())
    {
        $this->lastOutput = array();

        $command = $this->formatArguments($args);

        if ($this->quiet) {
            $this->logger->log('Executing: ' . $command);
        }

        $status = 0;
        $descriptorSpec = array(
            0 => array("pipe", "r"),  // stdin
            1 => array("pipe", "w"),  // stdout
            2 => array("pipe", "w"),  // stderr
        );

        $pipes = array();

        $process = proc_open($command, $descriptorSpec, $pipes, $this->buildPath, $this->environment->getArrayCopy());

        if (is_resource($process)) {
            fclose($pipes[0]);

            $this->lastOutput = stream_get_contents($pipes[1]);
            $this->lastError = stream_get_contents($pipes[2]);

            fclose($pipes[1]);
            fclose($pipes[2]);

            $status = proc_close($process);
        }

        $this->lastOutput = array_filter(explode(PHP_EOL, $this->lastOutput));

        $shouldOutput = ($this->logExecOutput && ($this->verbose || $status != 0));

        if ($shouldOutput && !empty($this->lastOutput)) {
            $this->logger->log($this->lastOutput);
        }

        if (!empty($this->lastError)) {
            $this->logger->log("\033[0;31m" . $this->lastError . "\033[0m", LogLevel::ERROR);
        }

        $rtn = false;

        if ($status == 0) {
            $rtn = true;
        }

        return $rtn;
    }

    /** Format the arguments into a single command.
     *
     * @param array $arguments
     * @return string
     */
    protected function formatArguments(array $arguments)
    {
        switch (count($arguments)) {
            case 0:
                // todo: throw an exception ?
                return '';
            case 1:
                return $arguments[0];
            default:
                return call_user_func_array('sprintf', $arguments);
        }
    }

    /**
     * Returns the output from the last command run.
     */
    public function getLastOutput()
    {
        return implode(PHP_EOL, $this->lastOutput);
    }

    /**
     * Returns the stderr output from the last command run.
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * Find a binary required by a plugin.
     *
     * @param array|string $binary
     *
     * @return string|null
     */
    public function findBinary($binary)
    {
        $binaryPath = null;
        if (is_string($binary)) {
            $binary = array($binary);
        }

        foreach ($binary as $bin) {
            $this->logger->log(Lang::get('looking_for_binary', $bin), LogLevel::DEBUG);

            if (is_file($this->rootDir . $bin)) {
                $this->logger->log(Lang::get('found_in_path', 'root', $bin), LogLevel::DEBUG);
                $binaryPath = $this->rootDir . $bin;
                break;
            }

            if (is_file($this->rootDir . 'vendor/bin/' . $bin)) {
                $this->logger->log(Lang::get('found_in_path', 'vendor/bin', $bin), LogLevel::DEBUG);
                $binaryPath = $this->rootDir . 'vendor/bin/' . $bin;
                break;
            }

            $findCmdResult = $this->findGlobalBinary($bin);
            if (is_file($findCmdResult)) {
                $this->logger->log(Lang::get('found_in_path', '', $bin), LogLevel::DEBUG);
                $binaryPath = $findCmdResult;
                break;
            }
        }
        return $binaryPath;
    }

    /**
     * Find a binary which is installed globally on the system
     *
     * @param string $binary
     *
     * @return null|string
     */
    abstract protected function findGlobalBinary($binary);

    /**
     * Set the buildPath property.
     *
     * @param string $path
     */
    public function setBuildPath($path)
    {
        $this->buildPath = $path;
    }
}
