<?php

namespace Kiboko\Component\ContinuousIntegration\Docker;

class EntrypointInstruction implements Instruction
{
    /**
     * @var CommandCombination
     */
    private $command;

    /**
     * RunInstruction constructor.
     * @param CommandCombination $command
     */
    public function __construct(CommandCombination $command)
    {
        $this->command = $command;
    }

    /**
     * @return CommandCombination
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param \SplFileObject $output
     */
    public function build(\SplFileObject $output)
    {
        $output->fwrite(sprintf('ENTRYPOINT "%s"', $this->command));
    }
}
