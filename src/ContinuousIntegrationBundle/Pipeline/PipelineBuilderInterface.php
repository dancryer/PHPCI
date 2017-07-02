<?php
namespace Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline;

use Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\Processor\ProcessorInterface;

interface PipelineBuilderInterface
{
    /**
     * Add an stage.
     *
     * @param StepInterface $stage
     *
     * @return $this
     */
    public function add(StepInterface $stage): PipelineBuilderInterface;

    /**
     * Build a new Pipeline object
     *
     * @param ProcessorInterface|null $processor
     *
     * @return PipelineInterface
     */
    public function build(ProcessorInterface $processor = null): PipelineInterface;
}
