<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\Processor;

use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\ExecutionContextInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\StageInterface;

interface ProcessorInterface
{
    /**
     * @param StageInterface[] $stages
     * @param BuildInterface $build
     * @param ExecutionContextInterface $executionContext
     * @param array $commandOptions
     *
     * @return ExecutionContextInterface
     */
    public function process(
        array $stages,
        BuildInterface $build,
        ExecutionContextInterface $executionContext,
        array $commandOptions = []
    ): ExecutionContextInterface;
}
