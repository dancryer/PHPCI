<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command;

class SubCommandInterface implements CommandInterface
{
    /**
     * @var CommandInterface
     */
    private $command;

    /**
     * CommandCombination constructor.
     *
     * @param CommandInterface $command
     */
    public function __construct(CommandInterface $command)
    {
        $this->command = $command;
    }

    public function __toString()
    {
        return '$(' . $this->command . ')';
    }
}
