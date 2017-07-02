<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext;

use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\CommandInterface;
use Symfony\Component\Process\Process;

interface ExecutionContextInterface
{
    /**
     * @return BuildInterface
     */
    public function getBuildInstance(): BuildInterface;

    /**
     * @return string
     */
    public function workingDirectory(): ?string;

    /**
     * @param CommandInterface $command
     *
     * @return Process
     */
    public function build(CommandInterface $command): Process;
}
