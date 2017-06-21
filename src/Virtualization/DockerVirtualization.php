<?php

namespace Kiboko\Component\ContinuousIntegration;

use Kiboko\Component\ContinuousIntegration\Docker\CommandCombination;
use Kiboko\Component\ContinuousIntegration\Docker\Image;
use Kiboko\Component\ContinuousIntegration\Docker\RunInstruction;

class DockerVirtualization implements Virtualization
{
    /**
     * @var Image
     */
    private $image;

    /**
     * @var \SplTempFileObject
     */
    private $buffer;

    /**
     * DockerVirtualization constructor.
     *
     * @param string $version
     */
    public function __construct($version)
    {
        $this->image = new Image('php', sprintf('%s-cli', $version));
        $this->image->push(new RunInstruction((new CommandCombination())->addCommand(['apt','update'])));

        $this->buffer = new \SplTempFileObject();
    }

    public function requirePackage(Package $package)
    {
        $package->register($this->image);
    }

    public function up()
    {
        // TODO: Implement up() method.
    }

    public function run($command)
    {
        // TODO: Implement run() method.
    }
}
