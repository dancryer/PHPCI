<?php

namespace PHPCI\Helper;


use PHPCI\BuildLogger;

class CommandExecutor
{
    /**
     * @var \PHPCI\BuildLogger
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

        if (!empty($this->lastOutput) && ($this->verbose|| $status != 0)) {
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
            // Check project root directory:
            if (is_file($this->rootDir . $bin)) {
                return $this->rootDir . $bin;
            }

            // Check Composer bin dir:
            if (is_file($this->rootDir . 'vendor/bin/' . $bin)) {
                return $this->rootDir . 'vendor/bin/' . $bin;
            }

            // Use "which"
            $which = trim(shell_exec('which ' . $bin));

            if (!empty($which)) {
                return $which;
            }
        }

        return null;
    }
}
