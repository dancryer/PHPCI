<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Plugin;

use PDO;

/**
* MySQL Plugin - Provides access to a MySQL database.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class Mysql implements \PHPCI\Plugin
{
    protected $phpci;
    protected $queries = array();

    protected $host;
    protected $user;
    protected $pass;

    public function __construct(\PHPCI\Builder $phpci, array $options = array())
    {
        $this->phpci        = $phpci;
        $this->queries      = $options;

        $config     = \b8\Database::getConnection('write')->getDetails();
        $this->host = PHPCI_DB_HOST;
        $this->user = $config['user'];
        $this->pass = $config['pass'];

        $buildSettings = $phpci->getConfig('build_settings');
        if (isset($buildSettings['mysql'])) {
            $sql    = $buildSettings['mysql'];

            $this->host = !empty($sql['host']) ? $sql['host'] : $this->host;
            $this->user = !empty($sql['user']) ? $sql['user'] : $this->user;
            $this->pass = array_key_exists('pass', $sql) ? $sql['pass'] : $this->pass;
        }
    }

    /**
    * Connects to MySQL and runs a specified set of queries.
    */
    public function execute()
    {
        try {
            $opts = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
            $pdo = new PDO('mysql:host=' . $this->host, $this->user, $this->pass, $opts);

            foreach ($this->queries as $query) {
                $pdo->query($query);
            }
        } catch (\Exception $ex) {
            $this->phpci->logFailure($ex->getMessage());
            return false;
        }

        return true;
    }
}
