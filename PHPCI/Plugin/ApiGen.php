<?php
namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;

/**
 * PHPCI ApiGen Plugin
 */
class ApiGen implements \PHPCI\Plugin
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
     * Standard Constructor
     *
     * @param Builder $phpci
     * @param Build   $build
     */
    public function __construct(Builder $phpci, Build $build)
    {
        // Basic
        $this->phpci = $phpci;
        $this->build = $build;
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
     * Plugin Execution
     *
     * @return boolean Result
     */
    public function execute()
    {
        $phpci      = $this->getPHPCI();
        $executable = $phpci->findBinary('apigen');

        if (!$executable) {
            $phpci->logFailure('Could not find ApiGen executable.');
            return false;
        }

        if (!is_readable($phpci->buildPath . '/apigen.neon')) {
            $phpci->logFailure('Could not find ApiGen configuration file.');
            return false;
        }

        // Push Path
        $path = getcwd();
        chdir($phpci->buildPath);

        $result = $phpci->executeCommand($executable . ' generate');

        // Pop Path
        chdir($path);

        return $result;
    }
}
