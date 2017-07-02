<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline;

use Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\Processor\ProcessorInterface;

class PipelineBuilder implements PipelineBuilderInterface
{
    /**
     * @var callable[]
     */
    private $stages = [];

    /**
     * Add an stage.
     *
     * @param StepInterface $stage
     *
     * @return PipelineBuilderInterface
     */
    public function add(StepInterface $stage): PipelineBuilderInterface
    {
        $this->stages[] = $stage;

        return $this;
    }

    /**
     * Build a new Pipeline object
     *
     * @param  ProcessorInterface|null $processor
     *
     * @return PipelineInterface
     */
    public function build(ProcessorInterface $processor = null): PipelineInterface
    {
        return new Pipeline($this->stages, $processor);
    }
}
