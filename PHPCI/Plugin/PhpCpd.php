<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;

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
    protected $build;

    /**
     * @var string, based on the assumption the root may not hold the code to be
     * tested, exteds the base path
     */
    protected $path;

    /**
     * @var array - paths to ignore
     */
    protected $ignore;

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;

        $this->path = $phpci->buildPath;
        $this->standard = 'PSR1';
        $this->ignore = $phpci->ignore;

        if (!empty($options['path'])) {
            $this->path = $phpci->buildPath . $options['path'];
        }

        if (!empty($options['standard'])) {
            $this->standard = $options['standard'];
        }

        if (!empty($options['ignore'])) {
            $this->ignore = $this->phpci->ignore;
        }
    }

    /**
    * Runs PHP Copy/Paste Detector in a specified directory.
    */
    public function execute()
    {
        $ignore = '';
        if (count($this->ignore)) {
            $map = function ($item) {
                // remove the trailing slash
                $item = (substr($item, -1) == '/' ? substr($item, 0, -1) : $item);

                if (is_file($this->path . '/' . $item)) {
                    return ' --names-exclude ' . $item;
                } else {
                    return ' --exclude ' . $item;
                }

            };
            $ignore = array_map($map, $this->ignore);

            $ignore = implode('', $ignore);
        }

        $phpcpd = $this->phpci->findBinary('phpcpd');

        if (!$phpcpd) {
            $this->phpci->logFailure('Could not find phpcpd.');
            return false;
        }

        $success = $this->phpci->executeCommand($phpcpd . ' %s "%s"', $ignore, $this->path);

        print $this->phpci->getLastOutput();

        return $success;
    }
}
