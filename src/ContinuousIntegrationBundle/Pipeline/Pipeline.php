<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline;

use InvalidArgumentException;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\ExecutionContextInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\Processor\FingersCrossedProcessor;
use Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\Processor\ProcessorInterface;

class Pipeline implements PipelineInterface
{
    /**
     * @var callable[]
     */
    private $steps = [];

    /**
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * Constructor.
     *
     * @param StageInterface[] $steps
     * @param ProcessorInterface $processor
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $steps = [], ProcessorInterface $processor = null)
    {
        foreach ($steps as $step) {
            if (false === is_callable($step)) {
                throw new InvalidArgumentException('All steps should be callable.');
            }
        }

        $this->steps = $steps;
        $this->processor = $processor ?: new FingersCrossedProcessor();
    }

    /**
     * @inheritdoc
     */
    public function pipe(StepInterface $step): PipelineInterface
    {
        $pipeline = clone $this;
        $pipeline->steps[] = $step;

        return $pipeline;
    }

    /**
     * Process the payload.
     *
     * @param BuildInterface $build
     * @param ExecutionContextInterface $executionContext
     * @param array $commandOptions
     *
     * @return ExecutionContextInterface
     */
    public function process(
        BuildInterface $build,
        ExecutionContextInterface $executionContext,
        array $commandOptions = []
    ): ExecutionContextInterface {
        return $this->processor->process($this->steps, $build, $executionContext, $commandOptions);
    }

    /**
     * @inheritdoc
     */
    public function __invoke(
        BuildInterface $build,
        ExecutionContextInterface $executionContext,
        array $commandOptions = []
    ): ExecutionContextInterface {
        return $this->process($build, $executionContext, $commandOptions);
    }
}
