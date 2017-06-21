<?php

namespace Kiboko\Component\ContinuousIntegration;

interface Virtualization
{
    /**
     * @param Package $package
     */
    public function requirePackage(Package $package);

    /**
     * @return bool
     */
    public function up();

    /**
     * @param string|array $command
     *
     * @return int
     */
    public function run($command);
}
