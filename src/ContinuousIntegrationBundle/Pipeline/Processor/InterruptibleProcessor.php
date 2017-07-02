<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\Processor;

use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\ExecutionContextInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\StepInterface;

class InterruptibleProcessor implements ProcessorInterface
{
    /**
     * @var ExecutionCheckerInterface
     */
    private $checker;

    /**
     * InterruptibleProcessor constructor.
     *
     * @param ExecutionCheckerInterface $checker
     */
    public function __construct(ExecutionCheckerInterface $checker)
    {
        $this->checker = $checker;
    }

    /**
     * @param StepInterface[] $steps
     * @param BuildInterface $build
     * @param ExecutionContextInterface $executionContext
     * @param array $commandOptions
     *
     * @return ExecutionContextInterface
     */
    public function process(
        array $steps,
        BuildInterface $build,
        ExecutionContextInterface $executionContext,
        array $commandOptions = []
    ): ExecutionContextInterface {
        foreach ($steps as $step) {
            $executionContext = $step($build, $executionContext, $commandOptions);

            if (true !== $this->checker->check($build, $executionContext)) {
                return $executionContext;
            }
        }

        return $executionContext;
    }
}
