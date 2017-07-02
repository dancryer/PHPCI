<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext;

use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\CommandInterface;
use Symfony\Component\Process\Process;

class ShellExecutionContext implements ExecutionContextInterface
{
    /**
     * @var BuildInterface
     */
    private $buildInstance;

    /**
     * @var string
     */
    private $workingDirectory;

    /**
     * ShellExecutionContext constructor.
     *
     * @param BuildInterface $buildInstance
     * @param string $workingDirectory
     */
    public function __construct(
        BuildInterface $buildInstance,
        ?string $workingDirectory = null
    ) {
        $this->buildInstance = $buildInstance;
        $this->workingDirectory = $workingDirectory ?: getcwd();
    }

    public function getBuildInstance(): BuildInterface
    {
        return $this->buildInstance;
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
        $processBuilder = new ProcessBuilder($command);
        $processBuilder->setWorkingDirectory($this->workingDirectory());

        return $processBuilder->getProcess();
    }
}
