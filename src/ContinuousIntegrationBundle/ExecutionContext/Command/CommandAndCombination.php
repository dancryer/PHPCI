<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command;

class CommandAndCombination implements CommandInterface
{
    /**
     * @var CommandInterface[]
     */
    private $commands;

    /**
     * CommandAndCombination constructor.
     *
     * @param CommandInterface[] $commands
     */
    public function __construct(CommandInterface ...$commands)
    {
        $this->commands = $commands;
    }

    /**
     * @param array ...$parameters
     *
     * @return CommandInterface
     */
    public function andX(...$parameters): CommandInterface
    {
        $this->commands = array_merge(
            $this->commands,
            $parameters
        );

        return $this;
    }

    public function __toString()
    {
        return implode(' && ', $this->commands);
    }
}
