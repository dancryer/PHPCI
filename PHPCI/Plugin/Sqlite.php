<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PDO;

/**
* SQLite Plugin — Provides access to a SQLite database.
* @author       Corpsee <poisoncorpsee@gmail.com>
* @package      PHPCI
* @subpackage   Plugins
*/
class Sqlite extends AbstractPlugin
{
    /**
     * @var array
     */
    protected $queries = array();

    /**
     * @var string
     */
    protected $path;

    /**
     * Configure the plugin.
     *
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        $this->queries = $options;
        $buildSettings = $this->phpci->getConfig('build_settings');

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
