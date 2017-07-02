<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline;

use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\Processor\ProcessorInterface;

interface StageInterface extends PipelineBuilderInterface
{
    /**
     * @param ProcessorInterface $processor
     * @param BuildInterface $build
     *
     * @return bool
     */
    public function __invoke(
        ProcessorInterface $processor,
        BuildInterface $build
    ): bool;
}
