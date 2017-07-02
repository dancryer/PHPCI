<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command;

class Command implements CommandInterface
{
    /**
     * @var string[]
     */
    private $commandOperands;

    /**
     * Command constructor.
     *
     * @param string[] $parameters
     */
    public function __construct(...$parameters)
    {
        $this->commandOperands = $parameters;
    }

    /**
     * Command constructor.
     *
     * @param string[] $parameters
     */
    public function push(...$parameters)
    {
        $this->commandOperands = array_merge(
            $this->commandOperands,
            $parameters
        );
    }

    public function __toString()
    {
        return implode(' ', array_map(function($item) {
            return !$item instanceof CommandInterface ? escapeshellarg($item) : $item;
        }, $this->commandOperands));
    }
}
