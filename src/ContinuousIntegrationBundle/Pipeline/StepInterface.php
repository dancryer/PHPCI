<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline;

use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\ExecutionContextInterface;

interface StepInterface
{
    /**
     * @param BuildInterface $build
     * @param ExecutionContextInterface $executionContext
     *
     * @return ExecutionContextInterface
     */
    public function __invoke(
        BuildInterface $build,
        ExecutionContextInterface $executionContext
    ): ExecutionContextInterface;
}
