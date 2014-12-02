<?php
namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;

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
     * @param array   $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
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

    // Execution
    public function execute()
    {
        return true;
    }
}
