<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Plugin\DockerCompose;

use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\AndCombinationCommand;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\Command;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\CommandInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\SubCommand;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\DockerExecutionContext;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\ExecutionContextInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\StepInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

class BuildStep implements StepInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * Docker constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->setLogger($logger);
    }

    /**
     * @param ExecutionContextInterface $executionContext
     * @param string $dockerfile
     * @param string $containerName
     * @param string $machineName
     *
     * @return CommandInterface
     */
    public function generateCommand(
        ExecutionContextInterface $executionContext,
        ?string $dockerfile,
        ?string $containerName,
        ?string $machineName = null
    ) {
        $command = new Command('docker-compose', 'build', '--pull', '--quiet');

        if ($dockerfile !== null) {
            $command->push('--file', $dockerfile);
        }

        if ($containerName !== null) {
            $command->push('--tag', $containerName);
        }

        $command->push($executionContext->workingDirectory());

        if ($machineName === null) {
            return $command;
        }

        return new AndCombinationCommand(
            new Command('eval', new SubCommand(new Command('docker-machine', 'env', $machineName))),
            $command
        );
    }

    /**
     * @param BuildInterface $build
     * @param ExecutionContextInterface $executionContext
     * @param array $commandOptions
     *
     * @return ExecutionContextInterface
     */
    public function __invoke(
        BuildInterface $build,
        ExecutionContextInterface $executionContext,
        array $commandOptions = []
    ): ExecutionContextInterface {
        $config = $build->getExtra()['docker'];

        $command = $this->generateCommand(
            $executionContext,
            $config['dockerfile'] ?? 'Dockerfile',
            $config['name'] ?? null,
            $config['machine'] ?? null
        );

        $this->logger->info('Running command ' . $command);

        $execution = $executionContext->build($command);
        $execution->run(function($type, $data) use(&$hash) {
            if ($type === Process::OUT) {
                $hash = $data;
            }
            if ($type === Process::ERR) {
                file_put_contents('php://stderr', $data, FILE_APPEND);
            }
        });

        return new DockerExecutionContext($executionContext, $hash);
    }

    /**
     * @param string $commandType
     *
     * @return bool
     */
    public function supportsCommand(string $commandType): bool
    {
        return $commandType === 'docker-compose.build';
    }
}
