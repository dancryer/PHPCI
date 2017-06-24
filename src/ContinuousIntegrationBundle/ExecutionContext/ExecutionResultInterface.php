<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext;

interface ExecutionResultInterface
{
    /**
     * @param int $signal
     *
     * @return bool
     */
    public function kill(int $signal): bool;

    /**
     * @return int
     */
    public function halt(): int;

    /**
     * @param int $timeout
     * @param callable $callback
     *
     * @return bool
     */
    public function wait(int $timeout, callable $callback): bool;

    /**
     * @return resource
     */
    public function inputStream();

    /**
     * @return resource
     */
    public function outputStream();

    /**
     * @return resource
     */
    public function errorStream();

    /**
     * @param bool $forceRefresh
     *
     * @return int
     */
    public function pid(bool $forceRefresh = false): int;

    /**
     * @param bool $forceRefresh
     *
     * @return bool
     */
    public function isRunning(bool $forceRefresh = false): bool;

    /**
     * @param bool $forceRefresh
     *
     * @return string
     */
    public function getCommand(bool $forceRefresh = false): string;

    /**
     * @param bool $forceRefresh
     *
     * @return bool
     */
    public function isSignaled(bool $forceRefresh = false): bool;

    /**
     * @param bool $forceRefresh
     *
     * @return bool
     */
    public function isStopped(bool $forceRefresh = false): bool;

    /**
     * @param bool $forceRefresh
     *
     * @return int
     */
    public function getExitCode(bool $forceRefresh = false): int;
}
