<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Worker;

interface WorkerInterface
{
    public function run(): void;

    public function stop(): void;
}
