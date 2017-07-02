<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline;

use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\ShellExecutionContext;
use Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\Processor\ProcessorInterface;

class Stage extends PipelineBuilder implements StageInterface
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $label;

    /**
     * Stage constructor.
     * @param string $code
     * @param string $label
     */
    public function __construct($code, $label)
    {
        $this->code = $code;
        $this->label = $label;
    }

    public function __invoke(
        ProcessorInterface $processor,
        BuildInterface $build
    ): bool {
        $pipeline = $this->build($processor);

        $pipeline(
            $build,
            new ShellExecutionContext($build)
        );
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }
}
