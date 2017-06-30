<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Plugin;

use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\Command;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\CommandInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\ExecutionContextInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\StepInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

class CommandStep implements StepInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var CommandInterface
     */
    private $command;

    /**
     * Docker constructor.
     *
     * @param LoggerInterface $logger
     * @param array $commandOptions
     */
    public function __construct(
        LoggerInterface $logger,
        array $commandOptions
    ) {
        $this->command = new Command($commandOptions);
        $this->setLogger($logger);
    }

    /**
     * @param BuildInterface $build
     * @param ExecutionContextInterface $executionContext
     *
     * @return ExecutionContextInterface
     */
    public function __invoke(
        BuildInterface $build,
        ExecutionContextInterface $executionContext
    ): ExecutionContextInterface {
        $this->logger->info('Running command ' . $this->command);

        $execution = $executionContext->build($this->command);
        $execution->run(function($type, $data) use(&$hash) {
            if ($type === Process::OUT) {
                $this->logger->info($data);
            }

            if ($type === Process::ERR) {
                $this->logger->error($data);
            }
        });

        return $executionContext;
    }

    /**
     * @param string $commandType
     *
     * @return bool
     */
    public function supportsCommand(string $commandType): bool
    {
        return $commandType === 'command';
    }
}
