<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\Processor;

use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\ExecutionContextInterface;

interface ExecutionCheckerInterface
{
    public function check(
        BuildInterface $build,
        ExecutionContextInterface $executionContext
    ): bool;
}
