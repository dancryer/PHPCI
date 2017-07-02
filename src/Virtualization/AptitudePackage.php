<?php

namespace Kiboko\Component\ContinuousIntegration;

use Kiboko\Component\ContinuousIntegration\Docker\CommandCombination;
use Kiboko\Component\ContinuousIntegration\Docker\Image;
use Kiboko\Component\ContinuousIntegration\Docker\RunInstruction;

class AptitudePackage implements Package
{
    /**
     * @var string[]
     */
    private $packages;

    /**
     * @var string[]
     */
    private $configureOptions;

    /**
     * @var string[]
     */
    private $aptitudeDependencies;

    /**
     * PeclPackage constructor.
     *
     * @param string[] $packages
     * @param string[] $configureOptions
     * @param string[] $aptitudeDependencies
     */
    public function __construct(
        array $packages,
        array $configureOptions = [],
        array $aptitudeDependencies = []
    ) {
        $this->packages = $packages;
        $this->configureOptions = $configureOptions;
        $this->aptitudeDependencies = $aptitudeDependencies;
    }

    /**
     * @return string[]
     */
    public function getPackages()
    {
        return $this->packages;
    }

    /**
     * @return string[]
     */
    public function getConfigureOptions()
    {
        return $this->configureOptions;
    }

    /**
     * @return string[]
     */
    public function getAptitudeDependencies()
    {
        return $this->aptitudeDependencies;
    }

    public function register(Image $image)
    {
        $command = new CommandCombination();

        if (!empty($this->aptitudeDependencies)) {
            $command->addCommand(array_merge(
                [
                    'apt-get',
                    'install',
                    '-y',
                ],
                $this->aptitudeDependencies
            ));
        }

        foreach ($this->configureOptions as $packageName => $options) {
            $command->addCommand(array_merge(
                [
                    'docker-php-ext-configure',
                    $packageName,
                ],
                $options
            ));
        }

        if (!empty($this->packages)) {
            $command->addCommand(array_merge(
                [
                    'docker-php-ext-install',
                    '-j$(nproc)',
                ],
                $this->packages
            ));
        }

        $image->push(new RunInstruction($command));
    }
}
