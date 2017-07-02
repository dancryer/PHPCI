<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command;

class UnfilteredCommand implements CommandInterface
{
    /**
     * @var string
     */
    private $command;

    /**
     * UnfilteredCommand constructor.
     *
     * @param string $command
     */
    public function __construct(string $command)
    {
        $this->command = $command;
    }

    public function __toString()
    {
        return $this->command;
    }
}
