<?php

namespace Kiboko\Component\ContinuousIntegration\Docker;

class ExposeInstruction implements Instruction
{
    /**
     * @var int[]
     */
    private $ports;

    /**
     * ExposeInstruction constructor.
     *
     * @param \int[] $ports
     */
    public function __construct(array $ports)
    {
        $this->ports = $ports;
    }

    /**
     * @return \int[]
     */
    public function getPorts()
    {
        return $this->ports;
    }

    /**
     * @param \SplFileObject $output
     */
    public function build(\SplFileObject $output)
    {
        $output->fwrite(sprintf('EXPOSE %s', implode(' ', array_map(function ($item) {
            return sprintf('%d', $item);
        }, $this->ports))));
    }
}
