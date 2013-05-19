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
* @author       Steve Kamerman <stevekamerman@gmail.com>
* @package      PHPCI
* @subpackage   Plugins
*/
class Mysql implements \PHPCI\Plugin
{
    
    /**
     * @var \PHPCI\Builder
     */
    protected $phpci;
    protected $queries = array();

    protected $host;
    protected $user;
    protected $pass;
    
    /**
     * Database Connection
     * @var PDO
     */
    protected $pdo;

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
        $success = true;
        
        try {
            $opts = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
            $this->pdo = new PDO('mysql:host=' . $this->host, $this->user, $this->pass, $opts);
            
            foreach ($this->queries as $query) {
                if (!is_array($query)) {
                    // Simple query
                    $this->pdo->query($query);
                } else if (isset($query['import'])) {
                    // SQL file execution
                    $this->executeFile($query['import']);
                }
            }
        } catch (\Exception $ex) {
            $this->phpci->logFailure($ex->getMessage());
            return false;
        }

        return $success;
    }
    
    protected function executeFile($query)
    {
        if (!isset($query['file'])) {
            throw new \Exception("Import statement must contiain an 'file' key");
        }
        
        $import_file = $query['file'];
        if (!is_readable($import_file)) {
        	throw new \Exception("Cannot open SQL import file");
        }
        
        $database = isset($query['database'])? $query['database']: null;
        
        $import_command = $this->getImportCommand($import_file, $database);
    	if (!$this->phpci->executeCommand($import_command)) {
    	    throw new \Exception("Unable to execute SQL file");
    	}
    	
        return true;
    }
    
    /**
     * Builds the MySQL import command required to import/execute the specified file
     * @param string $import_file Path to file, relative to the build root
     * @param string $database If specified, this database is selected before execution
     * @return string
     */
    protected function getImportCommand($import_file, $database=null) {
        $decompression = array(
            'bz2' => '| bzip2 --decompress',
            'gz' => '| gzip --decompress',
        );
        
        $extension = strtolower(pathinfo($import_file, PATHINFO_EXTENSION));
        $decomp_cmd = '';
        if (array_key_exists($extension, $decompression)) {
            $decomp_cmd = $decompression[$extension];
        }

        $args = array(
            ':import_file' => escapeshellarg($import_file),
            ':decomp_cmd' => $decomp_cmd,
            ':user' => escapeshellarg($this->user),
            ':pass' => escapeshellarg($this->pass),
            ':database' => ($database === null)? '': escapeshellarg($database),
        );
        return strtr('cat :import_file :decomp_cmd | mysql -u:user -p:pass :database', $args);
    }
}