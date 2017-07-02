<?php

namespace Kiboko\Component\ContinuousIntegration\Docker;

interface Instruction
{
    /**
     * @param \SplFileObject $output
     */
    public function build(\SplFileObject $output);
}
