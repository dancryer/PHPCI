<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext;

class ExecutionResult implements ExecutionResultInterface
{
    /**
     * @var resource
     */
    private $process;

    /**
     * @var resource
     */
    private $input;

    /**
     * @var resource
     */
    private $output;

    /**
     * @var resource
     */
    private $error;

    /**
     * @var string
     */
    private $command;

    /**
     * @var int
     */
    private $pid;

    /**
     * @var bool
     */
    private $isRunning;

    /**
     * @var bool
     */
    private $isSignaled;

    /**
     * @var bool
     */
    private $isStopped;

    /**
     * @var int
     */
    private $exitCode;

    /**
     * @var int
     */
    private $termSignal;

    /**
     * @var int
     */
    private $stopSignal;

    /**
     * ExecutionResult constructor.
     *
     * @param resource $process
     * @param resource $input
     * @param resource $output
     * @param resource $error
     */
    public function __construct($process, $input, $output, $error)
    {
        $this->process = $process;
        $this->input = $input;
        $this->output = $output;
        $this->error = $error;

        $this->refreshStatus();
    }

    /**
     * @param int $signal
     *
     * @return bool
     */
    public function kill(int $signal): bool
    {
        return proc_terminate($this->process, $signal);
    }

    /**
     * @return int
     */
    public function halt(): int
    {
        fclose($this->input);
        fclose($this->output);
        fclose($this->error);
        return proc_close($this->process);
    }

    /**
     * @param int $timeout
     * @param callable $callback
     *
     * @return bool
     */
    public function wait(int $timeout, callable $callback): bool
    {
        if ($this->isRunning(true)) {
            $read = [$this->input];
            $write = [$this->output];
            $except = [$this->error];
            $result = stream_select($read, $write, $except, $timeout);

            return $callback(
                $read[0] ?? null,
                $write[0] ?? null,
                $except[0] ?? null
            );
        }

        return $callback(
            is_resource($this->input) ? $this->input : null,
            is_resource($this->output) ? $this->output : null,
            is_resource($this->error) ? $this->error : null
        );
    }

    /**
     * @return resource
     */
    public function inputStream()
    {
        return $this->input;
    }

    /**
     * @return resource
     */
    public function outputStream()
    {
        return $this->output;
    }

    /**
     * @return resource
     */
    public function errorStream()
    {
        return $this->error;
    }

    private function refreshStatus()
    {
        $status = proc_get_status($this->process);

        $this->command = $status['command'];
        $this->pid = $status['pid'];
        $this->isRunning = $status['running'];
        $this->isSignaled = $status['signaled'];
        $this->isStopped = $status['stopped'];
        if ($this->isRunning === false && $this->exitCode === null) {
            $this->exitCode = $status['exitcode'];
        }
        $this->termSignal = $status['termsig'];
        $this->stopSignal = $status['stopsig'];
    }

    /**
     * @param bool $forceRefresh
     *
     * @return int
     */
    public function pid(bool $forceRefresh = false): int
    {
        if ($forceRefresh === true) {
            $this->refreshStatus();
        }
        return $this->pid;
    }

    /**
     * @param bool $forceRefresh
     *
     * @return bool
     */
    public function isRunning(bool $forceRefresh = false): bool
    {
        if ($forceRefresh === true) {
            $this->refreshStatus();
        }
        return $this->isRunning;
    }

    /**
     * @param bool $forceRefresh
     *
     * @return string
     */
    public function getCommand(bool $forceRefresh = false): string
    {
        if ($forceRefresh === true) {
            $this->refreshStatus();
        }
        return $this->command;
    }

    /**
     * @param bool $forceRefresh
     *
     * @return bool
     */
    public function isSignaled(bool $forceRefresh = false): bool
    {
        if ($forceRefresh === true) {
            $this->refreshStatus();
        }
        return $this->isSignaled;
    }

    /**
     * @param bool $forceRefresh
     *
     * @return bool
     */
    public function isStopped(bool $forceRefresh = false): bool
    {
        if ($forceRefresh === true) {
            $this->refreshStatus();
        }
        return $this->isStopped;
    }

    /**
     * @param bool $forceRefresh
     *
     * @return int
     */
    public function getExitCode(bool $forceRefresh = false): int
    {
        if ($forceRefresh === true) {
            $this->refreshStatus();
        }
        return $this->exitCode;
    }
}
