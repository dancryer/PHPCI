<?php

namespace Kiboko\Component\ContinuousIntegration\Docker;

class CommandCombination
{
    /**
     * @var string[][]
     */
    private $commands;

    /**
     * @param array $command
     *
     * @return $this
     */
    public function addCommand(array $command)
    {
        $this->commands[] = $command;

        return $this;
    }

    /**
     * @return \string[][]
     */
    public function getCommands()
    {
        return $this->commands;
    }

    public function __toString()
    {
        return implode('\\' . PHP_EOL . '    && ', array_map(function ($command) {
            return implode(' ', array_map(function ($item) {
                return sprintf('"%s"', addslashes($item));
            }, $command));
        }, $this->commands));
    }
}
