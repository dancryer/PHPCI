<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext;

use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\CommandInterface;

interface ExecutionContextInterface
{
    /**
     * @return string
     */
    public function workingDirectory(): ?string;

    /**
     * @param CommandInterface $command
     *
     * @return ExecutionResultInterface
     */
    public function run(CommandInterface $command): ExecutionResultInterface;
}
