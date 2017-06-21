<?php

namespace Kiboko\Component\ContinuousIntegration\Docker;

class FromInstruction implements Instruction
{
    /**
     * @var string
     */
    private $image;

    /**
     * @var string
     */
    private $tag;

    /**
     * From constructor.
     * @param string $image
     * @param string|null $tag
     */
    public function __construct($image, $tag = null)
    {
        $this->image = $image;
        $this->tag = $tag;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param \SplFileObject $output
     */
    public function build(\SplFileObject $output)
    {
        if ($this->tag === null) {
            $output->fwrite(sprintf('FROM %s', $this->image));
        } else {
            $output->fwrite(sprintf('FROM %s:%s', $this->image, $this->tag));
        }
    }
}
