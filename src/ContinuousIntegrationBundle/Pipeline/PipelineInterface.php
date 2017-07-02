<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline;

interface PipelineInterface extends StepInterface
{
    /**
     * Create a new pipeline with an appended stage.
     *
     * @param StepInterface $step
     *
     * @return PipelineInterface
     */
    public function pipe(StepInterface $step): PipelineInterface;
}
