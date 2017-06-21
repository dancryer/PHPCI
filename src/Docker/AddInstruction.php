<?php

namespace Kiboko\Component\ContinuousIntegration\Docker;

class AddInstruction implements Instruction
{
    /**
     * @var string
     */
    private $localPath;

    /**
     * @var string
     */
    private $containerPath;

    /**
     * CopyInstruction constructor.
     * @param string $localPath
     * @param string $containerPath
     */
    public function __construct($localPath, $containerPath)
    {
        $this->localPath = $localPath;
        $this->containerPath = $containerPath;
    }

    /**
     * @return string
     */
    public function getLocalPath()
    {
        return $this->localPath;
    }

    /**
     * @return string
     */
    public function getContainerPath()
    {
        return $this->containerPath;
    }

    /**
     * @param \SplFileObject $output
     */
    public function build(\SplFileObject $output)
    {
        $output->fwrite(sprintf('ADD "%s" "%s"', $this->localPath, $this->containerPath));
    }
}
