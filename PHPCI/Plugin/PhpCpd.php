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
use PHPCI\Helper\Lang;
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

    /**
     * Set up the plugin, configure options, etc.
     * @param Builder $phpci
     * @param Build $build
     * @param array $options
     */
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
            $this->ignore = $options['ignore'];
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
            $this->phpci->logFailure(Lang::get('could_not_find', 'phpcpd'));
            return false;
        }

        $tmpfilename = tempnam('/tmp', 'phpcpd');

        $cmd = $phpcpd . ' --log-pmd "%s" %s "%s"';
        $success = $this->phpci->executeCommand($cmd, $tmpfilename, $ignore, $this->path);

        print $this->phpci->getLastOutput();
        
        list($errorCount, $data) = $this->processReport(file_get_contents($tmpfilename));
        $this->build->storeMeta('phpcpd-warnings', $errorCount);
        $this->build->storeMeta('phpcpd-data', $data);

        unlink($tmpfilename);

        return $success;
    }

    /**
     * Process the PHPCPD XML report.
     * @param $xmlString
     * @return array
     * @throws \Exception
     */
    protected function processReport($xmlString)
    {
        $xml = simplexml_load_string($xmlString);

        if ($xml === false) {
            $this->phpci->log($xmlString);
            throw new \Exception(Lang::get('could_not_process_report'));
        }

        $warnings = 0;
        $data = array();

        foreach ($xml->duplication as $duplication) {
            foreach ($duplication->file as $file) {
                $fileName = (string)$file['path'];
                $fileName = str_replace($this->phpci->buildPath, '', $fileName);

                $data[] = array(
                    'file' => $fileName,
                    'line_start' => (int) $file['line'],
                    'line_end' => (int) $file['line'] + (int) $duplication['lines'],
                    'code' => (string) $duplication->codefragment
                );

                $message = <<<CPD
Copy and paste detected:

```
{$duplication->codefragment}
```
CPD;

                $this->build->reportError($this->phpci, $fileName, $file['line'], $message);

            }

            $warnings++;
        }

        return array($warnings, $data);
    }
}
