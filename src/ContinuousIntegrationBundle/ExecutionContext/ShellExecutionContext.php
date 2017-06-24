<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext;

use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\Command;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\CommandInterface;

class ShellExecutionContext implements ExecutionContextInterface
{
    /**
     * @var string
     */
    private $workingDirectory;

    /**
     * ShellExecutionContext constructor.
     *
     * @param string $workingDirectory
     */
    public function __construct(?string $workingDirectory = null)
    {
        $this->workingDirectory = $workingDirectory ?: getcwd();
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
        $process = proc_open(
            $command,
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'r'],
                2 => ['pipe', 'r'],
            ],
            $pipes,
            $this->workingDirectory
        );

        if ($process === false) {
            throw new \RuntimeException(
                sprintf('Could not open process [%s].', $command)
            );
        }

        list($input, $output, $error) = $pipes;
        stream_set_blocking($input, false);
        stream_set_blocking($output, false);
        stream_set_blocking($error, false);

        $status = new ExecutionResult($process, $input, $output, $error);

        if (!$status->isRunning()) {
            if (($exitCode = $status->getExitCode()) !== 0) {
                throw new \RuntimeException(sprintf(
                    'Process exited with code %d', $exitCode
                ));
            }
        }

        return $status;
    }
}
