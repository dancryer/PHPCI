<?php

namespace Kiboko\Component\ContinuousIntegration\Docker;

class WorkdirInstruction implements Instruction
{
    /**
     * @var string
     */
    private $path;

    /**
     * WorkdirInstruction constructor.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param \SplFileObject $output
     */
    public function build(\SplFileObject $output)
    {
        $output->fwrite(sprintf('WORKDIR "%s"', addslashes($this->path)));
    }
}
