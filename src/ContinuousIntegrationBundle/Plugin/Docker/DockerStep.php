<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Plugin\Docker;

use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\DockerExecutionContext;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\ExecutionContextInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\Pipeline;
use Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\Processor\ProcessorInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

class DockerStep extends Pipeline implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * Docker constructor.
     *
     * @param LoggerInterface $logger
     * @param callable[] $stages
     * @param ProcessorInterface $processor
     */
    public function __construct(
        LoggerInterface $logger,
        array $stages = [],
        ProcessorInterface $processor = null
    ) {
        parent::__construct($stages, $processor);

        $this->setLogger($logger);
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
        return $commandType === 'docker';
    }
}
