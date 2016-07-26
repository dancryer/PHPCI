<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 *
 * @link         https://www.phptesting.org/
 */
namespace PHPCI\Plugin;

use PDO;
use PHPCI\Builder;
use PHPCI\Model\Build;

/**
 * PgSQL Plugin - Provides access to a PgSQL database.
 *
 * @author       Dan Cryer <dan@block8.co.uk>
 */
class Pgsql implements \PHPCI\Plugin
{
    /**
     * @type \PHPCI\Builder
     */
    protected $phpci;

    /**
     * @type \PHPCI\Model\Build
     */
    protected $build;

    /**
     * @type array
     */
    protected $queries = [];

    /**
     * @type string
     */
    protected $host;

    /**
     * @type string
     */
    protected $user;

    /**
     * @type string
     */
    protected $pass;

    /**
     * @param Builder $phpci
     * @param Build   $build
     * @param array   $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = [])
    {
        $this->phpci   = $phpci;
        $this->build   = $build;
        $this->queries = $options;

        $buildSettings = $phpci->getConfig('build_settings');

        if (isset($buildSettings['pgsql'])) {
            $sql        = $buildSettings['pgsql'];
            $this->host = $sql['host'];
            $this->user = $sql['user'];
            $this->pass = $sql['pass'];
        }
    }

    /**
     * Connects to PgSQL and runs a specified set of queries.
     *
     * @return bool
     */
    public function execute()
    {
        try {
            $opts = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
            $pdo  = new PDO('pgsql:host=' . $this->host, $this->user, $this->pass, $opts);

            foreach ($this->queries as $query) {
                $pdo->query($this->phpci->interpolate($query));
            }
        } catch (\Exception $ex) {
            $this->phpci->logFailure($ex->getMessage());

            return false;
        }

        return true;
    }
}
