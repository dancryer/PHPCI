<?php
/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Kiboko\Component\ContinuousIntegration\Plugin;

use PDO;
use Kiboko\Component\ContinuousIntegration\Builder;
use Kiboko\Component\ContinuousIntegration\Model\Build;

/**
* SQLite Plugin — Provides access to a SQLite database.
* @author       Corpsee <poisoncorpsee@gmail.com>
* @package      PHPCI
* @subpackage   Plugins
*/
class Sqlite implements \Kiboko\Component\ContinuousIntegration\Plugin
{
    /**
     * @var \Kiboko\Component\ContinuousIntegration\Builder
     */
    protected $phpci;

    /**
     * @var \Kiboko\Component\ContinuousIntegration\Model\Build
     */
    protected $build;

    /**
     * @var array
     */
    protected $queries = array();

    /**
     * @var string
     */
    protected $path;

    /**
     * @param Builder $phpci
     * @param Build   $build
     * @param array   $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci   = $phpci;
        $this->build   = $build;
        $this->queries = $options;
        $buildSettings = $phpci->getConfig('build_settings');

        if (isset($buildSettings['sqlite'])) {
            $sql = $buildSettings['sqlite'];
            $this->path = $sql['path'];
        }
    }

    /**
     * Connects to SQLite and runs a specified set of queries.
     * @return boolean
     */
    public function execute()
    {
        try {
            $opts = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
            $pdo = new PDO('sqlite:' . $this->path, $opts);

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
