<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command;

class SubCommand implements CommandInterface
{
    /**
     * @var CommandInterface
     */
    private $command;

    /**
     * SubCommand constructor.
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
