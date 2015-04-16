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
* PgSQL Plugin - Provides access to a PgSQL database.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class Pgsql extends AbstractInterpolatingPlugin
{
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
     * Configure the plugin.
     *
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        $this->queries = $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function setCommonSettings(array $settings)
    {
        $this->host = $settings['host'];
        $this->user = $settings['user'];
        $this->pass = $settings['pass'];
    }

    /**
    * Connects to PgSQL and runs a specified set of queries.
    * @return boolean
    */
    public function execute()
    {
        try {
            $opts = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
            $pdo = new PDO('pgsql:host=' . $this->host, $this->user, $this->pass, $opts);

            foreach ($this->queries as $query) {
                $pdo->query($this->interpolator->interpolate($query));
            }
        } catch (\Exception $ex) {
            $this->logger->logFailure($ex->getMessage());
            return false;
        }
        return true;
    }
}
