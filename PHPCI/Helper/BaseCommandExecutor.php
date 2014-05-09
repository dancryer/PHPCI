<?php

namespace PHPCI\Helper;

use \PHPCI\Logging\BuildLogger;
use Psr\Log\LogLevel;

class BaseCommandExecutor implements CommandExecutor
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

    protected $lastOutput;

    public $logExecOutput = true;


    /**
     * The path which findBinary will look in.
     * @var string
     */
    protected $rootDir;

    /**
     * @param BuildLogger $logger
     * @param string      $rootDir
     * @param bool        $quiet
     * @param bool        $verbose
     */
    public function __construct(BuildLogger $logger, $rootDir, &$quiet = false, &$verbose = false)
    {
        $this->logger = $logger;
        $this->quiet = $quiet;
        $this->verbose = $verbose;

        $this->lastOutput = array();

        $this->rootDir = $rootDir;
    }

    /**
     * Executes shell commands.
     * @param array $args
     * @return bool Indicates success
     */
    public function executeCommand($args = array())
    {
        $this->lastOutput = array();

        $command = call_user_func_array('sprintf', $args);

        if ($this->quiet) {
            $this->logger->log('Executing: ' . $command);
        }

        $status = 0;
        exec($command, $this->lastOutput, $status);

        foreach ($this->lastOutput as &$lastOutput) {
            $lastOutput = trim($lastOutput, '"');
        }

        if ($this->logExecOutput && !empty($this->lastOutput) && ($this->verbose|| $status != 0)) {
            $this->logger->log($this->lastOutput);
        }

        $rtn = false;

        if ($status == 0) {
            $rtn = true;
        }

        return $rtn;
    }

    /**
     * Returns the output from the last command run.
     */
    public function getLastOutput()
    {
        return implode(PHP_EOL, $this->lastOutput);
    }

    /**
     * Find a binary required by a plugin.
     * @param string $binary
     * @return null|string
     */
    public function findBinary($binary)
    {
        $binaryPath = null;

        if (is_string($binary)) {
            $binary = array($binary);
        }

        foreach ($binary as $bin) {
            $this->logger->log("Looking for binary: " . $bin, LogLevel::DEBUG);
            // Check project root directory:
            if (is_file($this->rootDir . $bin)) {
                $this->logger->log("Found in root: " . $bin, LogLevel::DEBUG);
                $binaryPath = $this->rootDir . $bin;
                break;
            }

            // Check Composer bin dir:
            if (is_file($this->rootDir . 'vendor/bin/' . $bin)) {
                $this->logger->log("Found in vendor/bin: " . $bin, LogLevel::DEBUG);
                $binaryPath = $this->rootDir . 'vendor/bin/' . $bin;
                break;
            }
        }
        return $binaryPath;
    }
}
