<?php

namespace Kiboko\Component\ContinuousIntegration\Docker;

class Image
{
    /**
     * @var Instruction[]
     */
    private $instructions;

    /**
     * Image constructor.
     *
     * @param string $baseImage
     * @param string $baseImageTag
     */
    public function __construct($baseImage, $baseImageTag)
    {
        $this->instructions[] = new FromInstruction($baseImage, $baseImageTag);
    }

    /**
     * @param Instruction $instruction
     */
    public function push(Instruction $instruction)
    {
        $this->instructions[] = $instruction;
    }

    /**
     * @param \SplFileObject $output
     *
     * @return string
     */
    public function build(\SplFileObject $output)
    {
        foreach ($this->instructions as $instruction) {
            $instruction->build($output);

            $output->fwrite(PHP_EOL . PHP_EOL);
        }
    }
}
