<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline;

use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\Command;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\CommandAndCombination;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\SubCommandInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\DockerExecutionContext;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\ExecutionContextInterface;

class Docker implements StepInterface
{
    /**
     * @var int
     */
    private $waitTimeout;

    /**
     * Docker constructor.
     *
     * @param int $waitTimeout
     */
    public function __construct(int $waitTimeout = 0)
    {
        $this->waitTimeout = $waitTimeout;
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
        $config = $build->getExtra()['docker'];

        $command = new CommandAndCombination(
            new Command('eval', new SubCommandInterface(new Command('docker-machine', 'env'))),
            new Command('docker', 'build', '--pull',
                '--file', $config['dockerfile'] ?? 'Dockerfile',
                '--tag', $config['name'],
                $executionContext->workingDirectory()
            )
        );

        $execution = $executionContext->run(
            $command
        );

        do {
            $status = $execution->wait($this->waitTimeout, function($input, $output, $error): bool {
                if ($output !== null) {
                    stream_copy_to_stream($output, STDOUT);
                }
                if ($error) {
                    stream_copy_to_stream($error, STDERR);
                }
                return $input !== null || $output !== null || $error !== null;
            });
        } while ($status === true && $execution->isRunning(true));

        return new DockerExecutionContext($executionContext, $config['name']);
    }
}
