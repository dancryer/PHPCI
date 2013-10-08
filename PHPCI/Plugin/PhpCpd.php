<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Plugin;

/**
* PHP Copy / Paste Detector - Allows PHP Copy / Paste Detector testing.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpCpd implements \PHPCI\Plugin
{
    protected $directory;
    protected $args;
    protected $phpci;

    /**
     * @var string, based on the assumption the root may not hold the code to be
     * tested, exteds the base path
     */
    protected $path;

    /**
     * @var array - paths to ignore
     */
    protected $ignore;

    public function __construct(\PHPCI\Builder $phpci, array $options = array())
    {
        $this->phpci        = $phpci;
        $this->directory    = isset($options['directory']) ? $options['directory'] : $phpci->buildPath;
        $this->standard     = isset($options['standard']) ? $options['standard'] : 'PSR2';
        $this->path         = (isset($options['path'])) ? $options['path'] : '';
        $this->ignore       = (isset($options['ignore'])) ? (array)$options['ignore'] : $this->phpci->ignore;

    }

    /**
    * Runs PHP Copy/Paste Detector in a specified directory.
    */
    public function execute()
    {
        $ignore = '';
        if (count($this->ignore)) {
            $map = function ($item) {
                return ' --exclude ' . (substr($item, -1) == '/' ? substr($item, 0, -1) : $item);
            };
            $ignore = array_map($map, $this->ignore);

            $ignore = implode('', $ignore);
        }

        $success = $this->phpci->executeCommand(PHPCI_BIN_DIR . 'phpcpd %s "%s"', $ignore, $this->phpci->buildPath.$this->path);

        print $this->phpci->getLastOutput();

        return $success;
    }
}
