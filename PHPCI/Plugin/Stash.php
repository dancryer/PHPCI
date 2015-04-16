<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use b8\View;
use PHPCI\Builder;
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;

/**
* Stash Plugin - Provides simple stash capability to PHPCI.
* @author       Alasdair Campbell <alasdair@campbelldiagnostics.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class Stash implements \PHPCI\Plugin
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
    protected $options;

    /**
     * @var string
     */
    protected $phpciHostname = null;

    /**
     * @var string
     */
    protected $stashHostname = null;

    /**
     * @var integer
     */
    protected $stashPort = 7990;

    /**
     * @var string
     */
    protected $username = null;

    /**
     * @var string
     */
    protected $password = null;

    /**
     * Set up the plugin, configure options, etc.
     * @param Builder $phpci
     * @param Build $build
     * @param array $options
     */
    public function __construct(
        Builder $phpci,
        Build $build,
        array $options = array()
    ) {
        $this->phpci        = $phpci;
        $this->build        = $build;
        $this->options      = $options;
        
        $this->setProperties();
    }

    /**
     * Sends the notification to stash.
     */
    public function execute()
    {

        $buildStatus  = $this->build->isSuccessful() ? "SUCCESSFUL" : "FAILED";
        $buildId  = $this->build->getId();
        $commitId  = $this->build->getCommitId();
        $projectName  = $this->build->getProject()->getTitle();

        $buildUrl = "http://" . $this->phpciHostname . "/build/view/" . $buildId;
        $url = 'http://' . $this->stashHostname . ':' . $this->stashPort;
        $url .= '/rest/build-status/1.0/commits/' . $commitId;

        $data = array("state" => $buildStatus, "key" => $projectName, "url" => $buildUrl);
        $data_string = json_encode($data);
         
        try {
            $handle = curl_init($url);
            curl_setopt($handle, CURLOPT_USERPWD, $this->username . ":" . $this->password);
            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(
                $handle,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string)
                )
            );
          
            $result = curl_exec($handle);

            $this->phpci->log("Sent to Stash.");

            if ($result === false) {
                $this->phpci->log("Unknown issue with curl request");
                return false;
            }
            return true;
        } catch (Exception $e) {
            $this->phpci->log($e->getMessage);
            return false;
        }
    }

    /**
     * Sets the class properties from the YAML config
     */
    protected function setProperties()
    {
        if (isset($this->options['phpci_hostname'])) {
            $this->phpciHostname = $this->options['phpci_hostname'];
        }

        if (isset($this->options['stash_hostname'])) {
            $this->stashHostname = $this->options['stash_hostname'];
        }

        if (isset($this->options['stash_port'])) {
            $this->stashPort = $this->options['stash_port'];
        }

        if (isset($this->options['username'])) {
            $this->username = $this->options['username'];
        }

        if (isset($this->options['password'])) {
            $this->password = $this->options['password'];
        }
    }
}
