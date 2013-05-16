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
* PHP Code Sniffer Plugin - Allows PHP Code Sniffer testing.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpCodeSniffer implements \PHPCI\Plugin
{
    protected $directory;
    protected $args;
    protected $phpci;

    public function __construct(\PHPCI\Builder $phpci, array $options = array())
    {
        $this->phpci        = $phpci;
        $this->directory    = isset($options['directory']) ? $options['directory'] : $phpci->buildPath;
        $this->standard     = isset($options['standard']) ? $options['standard'] : 'PSR2';
    }

    public function execute()
    {
        $ignore = '';
        
        if (count($this->phpci->ignore)) {
            $map = function ($item) {
                return substr($item, -1) == '/' ? $item . '*' : $item . '/*';
            };

            $ignore = array_map($map, $this->phpci->ignore);

            $ignore = ' --ignore=' . implode(',', $ignore);
        }

        $cmd = PHPCI_BIN_DIR . 'phpcs --standard=%s %s "%s"';
        return $this->phpci->executeCommand($cmd, $this->standard, $ignore, $this->phpci->buildPath);
    }
}
