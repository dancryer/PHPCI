<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI\Helper\Lang;

/**
 * Behat BDD Plugin
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Behat extends AbstractPlugin
{
    protected $features;

    /**
     * Configure the plugin.
     *
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        $this->features = '';

        if (isset($options['executable'])) {
            $this->executable = $options['executable'];
        } else {
            $this->executable = $this->phpci->findBinary('behat');
        }

        if (!empty($options['features'])) {
            $this->features = $options['features'];
        }
    }

    /**
     * Runs Behat tests.
     */
    public function execute()
    {
        $curdir = getcwd();
        chdir($this->buildPath);

        $behat = $this->executable;

        if (!$behat) {
            $this->phpci->logFailure(Lang::get('could_not_find', 'behat'));

            return false;
        }

        $success = $this->phpci->executeCommand($behat . ' %s', $this->features);
        chdir($curdir);

        list($errorCount, $data) = $this->parseBehatOutput();

        $this->build->storeMeta('behat-warnings', $errorCount);
        $this->build->storeMeta('behat-data', $data);

        return $success;
    }

    /**
     * Parse the behat output and return details on failures
     *
     * @return array
     */
    public function parseBehatOutput()
    {
        $output = $this->phpci->getLastOutput();

        $parts = explode('---', $output);

        if (count($parts) <= 1) {
            return array(0, array());
        }

        $lines = explode(PHP_EOL, $parts[1]);

        $storeFailures = false;
        $data = array();

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line == 'Failed scenarios:') {
                $storeFailures = true;
                continue;
            }

            if (strpos($line, ':') === false) {
                $storeFailures = false;
            }

            if ($storeFailures) {
                $lineParts = explode(':', $line);
                $data[] = array(
                    'file' => $lineParts[0],
                    'line' => $lineParts[1]
                );

                $this->build->reportError($this->phpci, $lineParts[0], $lineParts[1], 'Behat scenario failed.');
            }
        }

        $errorCount = count($data);

        return array($errorCount, $data);
    }
}
