<?php
namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;

/**
 * Phar Plugin
 */
class Phar implements \PHPCI\Plugin
{
    /**
     * PHPCI
     * @var Builder
     */
    protected $phpci;

    /**
     * Build
     * @var Build
     */
    protected $build;

    /**
     * Output Directory
     * @var string
     */
    protected $directory;

    /**
     * Phar Filename
     * @var string
     */
    protected $filename;

    /**
     * Standard Constructor
     *
     * $options['directory'] Output Directory. Default: %BUILDPATH%
     * $options['filename']  Phar Filename. Default: build.phar
     *
     * @param Builder $phpci
     * @param Build   $build
     * @param array   $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        // Basic
        $this->phpci = $phpci;
        $this->build = $build;

        // Directory?
        if (isset($options['directory'])) {
            $this->setDirectory($options['directory']);
        }

        // Filename?
        if (isset($options['filename'])) {
            $this->setFilename($options['filename']);
        }
    }

    /**
     * Returns PHPCI
     *
     * @return PHPCI
     */
    public function getPHPCI()
    {
        return $this->phpci;
    }

    /**
     * Returns Build
     *
     * @return Build
     */
    public function getBuild()
    {
        return $this->build;
    }

    /**
     * Directory Setter
     *
     * @param  string $directory Configuration Value
     * @return Phar   Fluent Interface
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
        return $this;
    }

    /**
     * Directory Getter
     *
     * @return string Configurated or Default Value
     */
    public function getDirectory()
    {
        if (!isset($this->directory)) {
            $this->setDirectory($this->getPHPCI()->buildPath);
        }
        return $this->directory;
    }

    /**
     * Filename Setter
     *
     * @param  string $filename Configuration Value
     * @return Phar   Fluent Interface
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * Filename Getter
     *
     * @return string Configurated or Default Value
     */
    public function getFilename()
    {
        if (!isset($this->filename)) {
            $this->setFilename('build.phar');
        }
        return $this->filename;
    }

    // Execution
    public function execute()
    {
    }
}
