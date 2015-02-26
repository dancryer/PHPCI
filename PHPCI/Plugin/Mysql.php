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
use PHPCI\Builder;
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;

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

    /**
     * @var \PHPCI\Model\Build
     */
    protected $build;

    /**
     * @var array
     */
    protected $queries = array();

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $pass;

    /**
     * @param Builder $phpci
     * @param Build   $build
     * @param array   $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;

        $this->queries = $options;

        $config = \b8\Database::getConnection('write')->getDetails();

        $this->host =(defined('PHPCI_DB_HOST')) ? PHPCI_DB_HOST : null;
        $this->user = $config['user'];
        $this->pass = $config['pass'];

        $buildSettings = $phpci->getConfig('build_settings');

        if (!isset($buildSettings['mysql'])) {
            return;
        }

        if (!empty($buildSettings['mysql']['host'])) {
            $this->host = $this->phpci->interpolate($buildSettings['mysql']['host']);
        }

        if (!empty($buildSettings['mysql']['user'])) {
            $this->user = $this->phpci->interpolate($buildSettings['mysql']['user']);
        }

        if (array_key_exists('pass', $buildSettings['mysql'])) {
            $this->pass = $buildSettings['mysql']['pass'];
        }
    }

    /**
    * Connects to MySQL and runs a specified set of queries.
    * @return boolean
    */
    public function execute()
    {
        try {
            $opts = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
            $pdo  = new PDO('mysql:host=' . $this->host, $this->user, $this->pass, $opts);

            foreach ($this->queries as $query) {
                if (!is_array($query)) {
                    // Simple query
                    $pdo->query($this->phpci->interpolate($query));
                } elseif (isset($query['import'])) {
                    // SQL file execution
                    $this->executeFile($query['import']);
                } else {
                    throw new \Exception(Lang::get('invalid_command'));
                }
            }
        } catch (\Exception $ex) {
            $this->phpci->logFailure($ex->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @param string $query
     * @return boolean
     * @throws \Exception
     */
    protected function executeFile($query)
    {
        if (!isset($query['file'])) {
            throw new \Exception(Lang::get('import_file_key'));
        }

        $import_file = $this->phpci->buildPath . $this->phpci->interpolate($query['file']);
        if (!is_readable($import_file)) {
            throw new \Exception(Lang::get('cannot_open_import', $import_file));
        }

        $database = isset($query['database']) ? $this->phpci->interpolate($query['database']) : null;

        $import_command = $this->getImportCommand($import_file, $database);
        if (!$this->phpci->executeCommand($import_command)) {
            throw new \Exception(Lang::get('unable_to_execute'));
        }

        return true;
    }

    /**
     * Builds the MySQL import command required to import/execute the specified file
     * @param string $import_file Path to file, relative to the build root
     * @param string $database If specified, this database is selected before execution
     * @return string
     */
    protected function getImportCommand($import_file, $database = null)
    {
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
