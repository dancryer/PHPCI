<?php

namespace PHPCI\Helper;


use \PHPCI\Logging\BuildLogger;
use Psr\Log\LogLevel;

class CommandExecutor
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
     * @param $rootDir
     * @param bool $quiet
     * @param bool $verbose
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
     * Executes shell commands. Accepts multiple arguments the first
     * is the template and everything else is inserted in. c.f. sprintf
     * @return bool Indicates success
     */
    public function executeCommand()
    {
        return $this->buildAndExecuteCommand(func_get_args());
    }

    /**
     * Executes shell commands.
     * @param array $args
     * @return bool Indicates success
     */
    public function buildAndExecuteCommand($args = array())
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
     * @param $binary
     * @return null|string
     */
    public function findBinary($binary)
    {
        if (is_string($binary)) {
            $binary = array($binary);
        }

        foreach ($binary as $bin) {
            $this->logger->log("Looking for binary: " . $bin, LogLevel::DEBUG);
            // Check project root directory:
            if (is_file($this->rootDir . $bin)) {
                $this->logger->log("Found in root: " . $bin, LogLevel::DEBUG);
                return $this->rootDir . $bin;
            }

            // Check Composer bin dir:
            if (is_file($this->rootDir . 'vendor/bin/' . $bin)) {
                $this->logger->log("Found in vendor/bin: " . $bin, LogLevel::DEBUG);
                return $this->rootDir . 'vendor/bin/' . $bin;
            }

            // Use "where" for windows and "which" for other OS
            $findCmd       = IS_WIN ? 'where' : 'which';
            $findCmdResult = trim(shell_exec($findCmd . ' ' . $bin));

            if (!empty($findCmdResult)) {
                $this->logger->log("Found in " . $findCmdResult, LogLevel::DEBUG);
                return $findCmdResult;
            }
        }

        return null;
    }
}
