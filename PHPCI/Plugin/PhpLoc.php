<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI;
use PHPCI\Builder;
use PHPCI\Model\Build;

/**
 * PHP Loc - Allows PHP Copy / Lines of Code testing.
 *
 * @author       Johan van der Heide <info@japaveh.nl>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class PhpLoc implements PHPCI\Plugin, PHPCI\ZeroConfigPlugin
{
    /**
     * @var string
     */
    protected $directory;
    /**
     * @var \PHPCI\Builder
     */
    protected $phpci;

    /**
     * @var Build
     */
    protected $build;

    /**
     * Check if this plugin can be executed.
     *
     * @param         $stage
     * @param Builder $builder
     * @param Build   $build
     *
     * @return bool
     */
    public static function canExecute($stage, Builder $builder, Build $build)
    {
        if ($stage == 'test') {
            return true;
        }

        return false;
    }

    /**
     * Set up the plugin, configure options, etc.
     *
     * @param Builder $phpci
     * @param Build   $build
     * @param array   $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;
        $this->directory = $phpci->buildPath;

        if (isset($options['directory'])) {
            $this->directory .= $options['directory'];
        }
    }

    /**
     * Runs PHP Copy/Paste Detector in a specified directory.
     */
    public function execute()
    {
        $ignore = '';

        if (count($this->phpci->ignore)) {
            $map = function ($item) {
                return ' --exclude ' . rtrim($item, DIRECTORY_SEPARATOR);
            };

            $ignore = array_map($map, $this->phpci->ignore);
            $ignore = implode('', $ignore);
        }

        $phploc = $this->phpci->findBinary('phploc');

        $success = $this->phpci->executeCommand($phploc . ' %s "%s"', $ignore, $this->directory);
        $output = $this->phpci->getLastOutput();

        if (preg_match_all('/(Namespaces|Interfaces|Classes|Methods)\s+([0-9]+)/', $output, $matches)) {
            $matches = $matches[1];
            $data = array(
                $matches[1] = (int) $matches[1],
                $matches[2] = (int) $matches[2],
                $matches[3] = (int) $matches[3],
                $matches[6] = (int) $matches[6],
            );

            $this->build->storeMeta('phploc-structure', $data);
        }

        if (preg_match_all('/\((LOC|CLOC|NCLOC|LLOC)\)\s+([0-9]+)/', $output, $matches2)) {
            $data = array();
            foreach ($matches2[1] as $k => $v) {
                $data[$v] = (int) $matches2[2][$k];
            }

            $this->build->storeMeta('phploc', $data);
        }
        $this->build->storeMeta('phploc-test', array("test"=>"val"));
        return $success;
    }
}
