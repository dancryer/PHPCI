<?php
namespace PHPCI\Plugin;

use Exception;
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;
use Phar as PHPPhar;

/**
 * Phar Plugin
 */
class Phar extends AbstractPlugin
{
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
     * Regular Expression Filename Capture
     * @var string
     */
    protected $regexp;

    /**
     * Stub Filename
     * @var string
     */
    protected $stub;

    /**
     * Configure the plugin.
     *
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        // Directory?
        if (isset($options['directory'])) {
            $this->setDirectory($options['directory']);
        }

        // Filename?
        if (isset($options['filename'])) {
            $this->setFilename($options['filename']);
        }

        // RegExp?
        if (isset($options['regexp'])) {
            $this->setRegExp($options['regexp']);
        }

        // Stub?
        if (isset($options['stub'])) {
            $this->setStub($options['stub']);
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

    /**
     * Regular Expression Setter
     *
     * @param  string $regexp Configuration Value
     * @return Phar   Fluent Interface
     */
    public function setRegExp($regexp)
    {
        $this->regexp = $regexp;
        return $this;
    }

    /**
     * Regular Expression Getter
     *
     * @return string Configurated or Default Value
     */
    public function getRegExp()
    {
        if (!isset($this->regexp)) {
            $this->setRegExp('/\.php$/');
        }
        return $this->regexp;
    }

    /**
     * Stub Filename Setter
     *
     * @param  string $stub Configuration Value
     * @return Phar   Fluent Interface
     */
    public function setStub($stub)
    {
        $this->stub = $stub;
        return $this;
    }

    /**
     * Stub Filename Getter
     *
     * @return string Configurated Value
     */
    public function getStub()
    {
        return $this->stub;
    }

    /**
     * Get stub content for the Phar file.
     * @return string
     */
    public function getStubContent()
    {
        $content  = '';
        $filename = $this->getStub();
        if ($filename) {
            $content = file_get_contents($this->getPHPCI()->buildPath . '/' . $this->getStub());
        }
        return $content;
    }

    /**
     * Run the phar plugin.
     * @return bool
     */
    public function execute()
    {
        $phar = new PHPPhar($this->getDirectory() . '/' . $this->getFilename(), 0, $this->getFilename());
        $phar->buildFromDirectory($this->getPHPCI()->buildPath, $this->getRegExp());

        $stub = $this->getStubContent();
        if ($stub) {
            $phar->setStub($stub);
        }

        return true;
    }
}
