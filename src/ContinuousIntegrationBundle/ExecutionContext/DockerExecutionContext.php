<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext;

use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\CommandInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\CommandProxy;
use Symfony\Component\Process\Process;

class DockerExecutionContext implements ExecutionContextInterface
{
    /**
     * @var ExecutionContextInterface
     */
    private $parent;

    /**
     * @var string
     */
    private $containerNameOrHash;

    /**
     * @var string
     */
    private $workingDirectory;

    /**
     * DockerExecutionContext constructor.
     *
     * @param ExecutionContextInterface $parent
     * @param string $containerNameOrHash
     */
    public function __construct(
        ExecutionContextInterface $parent,
        string $containerNameOrHash
    ) {
        $this->parent = $parent;
        $this->containerNameOrHash = $containerNameOrHash;
    }

    /**
     * @return BuildInterface
     */
    public function getBuildInstance(): BuildInterface
    {
        return $this->parent->getBuildInstance();
    }

    /**
     * @return string
     */
    public function workingDirectory(): ?string
    {
        return $this->workingDirectory;
    }

    /**
     * @param CommandInterface $command
     *
     * @return Process
     */
    public function build(CommandInterface $command): Process
    {
        return $this->parent->build(new CommandProxy($command, 'docker', 'run', $this->containerNameOrHash));
    }
}
