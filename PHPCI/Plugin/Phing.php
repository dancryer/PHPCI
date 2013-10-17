<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2013, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         http://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;

/**
 * Phing Plugin - Provides access to Phing functionality.
 *
 * @author       Pavel Pavlov <ppavlov@alera.ru>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Phing implements \PHPCI\Plugin
{

    private $directory;
    private $buildFile;
    private $targets;
    private $properties;
    private $propertyFile;

    protected $phpci;

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this
            ->setDirectory(
                isset($options['directory']) ? $phpci->buildPath . '/' . $options['directory'] : $phpci->buildPath
            )
            ->setPhpci($phpci)
            ->setBuildFile(isset($options['build_file']) ? $options['build_file'] : 'build.xml')
            ->setTargets(isset($options['targets']) ? $options['targets'] : 'build')
            ->setProperties(isset($options['properties']) ? $options['properties'] : []);

        if (isset($options['property_file'])) {
            $this->setPropertyFile($options['property_file']);
        }
    }

    /**
     * Executes Phing and runs a specified targets
     */
    public function execute()
    {
        $phingExecutable = $this->phpci->findBinary('phing');

        if (!$phingExecutable) {
            $this->phpci->logFailure('Could not find Phing executable.');
            return false;
        }

        $cmd[] = $phingExecutable . ' -f ' . $this->getBuildFile();

        if ($this->getPropertyFile()) {
            $cmd[] = '-propertyfile ' . $this->getPropertyFile();
        }

        $cmd[] = $this->propertiesToString();

        $cmd[] = '-logger phing.listener.DefaultLogger';
        $cmd[] = $this->targetsToString();
        $cmd[] = '2>&1';

        return $this->phpci->executeCommand(implode(' ', $cmd), $this->directory, $this->targets);
    }

    /**
     * @return \PHPCI\Builder
     */
    public function getPhpci()
    {
        return $this->phpci;
    }

    /**
     * @param \PHPCI\Builder $phpci
     *
     * @return $this
     */
    public function setPhpci($phpci)
    {
        $this->phpci = $phpci;
        return $this;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $directory
     *
     * @return $this
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
        return $this;
    }

    /**
     * @return string
     */
    public function getTargets()
    {
        return $this->targets;
    }

    private function targetsToString()
    {
        return implode(' ', $this->targets);
    }

    /**
     * @param array|string $targets
     *
     * @return $this
     */
    public function setTargets($targets)
    {
        if (is_string($targets)) {
            $targets = array($targets);
        }

        $this->targets = $targets;
        return $this;
    }

    /**
     * @return string
     */
    public function getBuildFile()
    {
        return $this->buildFile;
    }

    /**
     * @param mixed $buildFile
     *
     * @return $this
     * @throws \Exception
     */
    public function setBuildFile($buildFile)
    {
        $buildFile = $this->getDirectory() . $buildFile;
        if (!file_exists($buildFile)) {
            throw new \Exception('Specified build file does not exists.');
        }

        $this->buildFile = $buildFile;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return string
     */
    public function propertiesToString()
    {
        if (empty($this->properties)) {
            return '';
        }

        $propertiesString = array();

        foreach ($this->properties as $name => $value) {
            $propertiesString[] = '-D' . $name . '="' . $value . '"';
        }

        return implode(' ', $propertiesString);
    }

    /**
     * @param array|string $properties
     *
     * @return $this
     */
    public function setProperties($properties)
    {
        if (is_string($properties)) {
            $properties = array($properties);
        }

        $this->properties = $properties;
        return $this;
    }

    /**
     * @return string
     */
    public function getPropertyFile()
    {
        return $this->propertyFile;
    }

    /**
     * @param string $propertyFile
     *
     * @return $this
     * @throws \Exception
     */
    public function setPropertyFile($propertyFile)
    {
        if (!file_exists($this->getDirectory() . '/' . $propertyFile)) {
            throw new \Exception('Specified property file does not exists.');
        }

        $this->propertyFile = $propertyFile;
        return $this;
    }
}
