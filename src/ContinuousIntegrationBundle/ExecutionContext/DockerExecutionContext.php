<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext;

use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\CommandInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\CommandProxy;

class DockerExecutionContext implements ExecutionContextInterface
{
    /**
     * @var ExecutionContextInterface
     */
    private $parent;

    /**
     * @var string
     */
    private $containerName;

    /**
     * @var string
     */
    private $workingDirectory;

    /**
     * DockerExecutionContext constructor.
     *
     * @param ExecutionContextInterface $parent
     * @param string $containerName
     */
    public function __construct(
        ExecutionContextInterface $parent,
        string $containerName
    ) {
        $this->parent = $parent;
        $this->containerName = $containerName;
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
     * @return ExecutionResultInterface
     */
    public function run(CommandInterface $command): ExecutionResultInterface
    {
        return $this->parent->run(new CommandProxy($command, 'docker', 'run', $this->containerName));
    }
}
