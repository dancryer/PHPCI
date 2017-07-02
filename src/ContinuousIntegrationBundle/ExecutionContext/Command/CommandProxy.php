<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command;

class CommandProxy extends Command
{
    /**
     * @var CommandInterface
     */
    private $decorated;

    /**
     * CommandProxy constructor.
     *
     * @param CommandInterface $decorated
     * @param array $parameters
     */
    public function __construct(CommandInterface $decorated, ...$parameters)
    {
        $this->decorated = $decorated;
        parent::__construct($parameters);
    }

    public function __toString()
    {
        return parent::__toString() . ' ' . $this->decorated->__toString();
    }
}
