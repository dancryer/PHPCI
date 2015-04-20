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
    }

    /**
     * Sends the notification to stash.
     */
    public function execute()
    {
        $required_keys = array('phpci_hostname', 'stash_hostname', 'stash_username', 'stash_password');

        if (count(array_diff($required_keys, array_keys(array_filter($this->options)))) != 0) {
            $this->phpci->log("All of the values '" . implode("', '", $required_keys) . " must be specified.");
            return false;
        }

        $phpciHostname = $this->options['phpci_hostname'];
        $stashHostname = $this->options['stash_hostname'];
        $stashPort = (isset($this->options['stash_port'])) ? $this->options['stash_port'] : 7990;
        $stashUsername = $this->options['stash_username'];
        $stashPassword = $this->options['stash_password'];

        $buildStatus  = $this->build->isSuccessful() ? "SUCCESSFUL" : "FAILED";
        $buildId  = $this->build->getId();
        $commitId  = $this->build->getCommitId();
        $projectName  = $this->build->getProject()->getTitle();

        $buildUrl = "http://" . $phpciHostname . "/build/view/" . $buildId;
        $url = 'http://' . $stashHostname . ':' . $stashPort;
        $url .= '/rest/build-status/1.0/commits/' . $commitId;

        $data = array("state" => $buildStatus, "key" => $projectName, "url" => $buildUrl);
        $data_string = json_encode($data);
     
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_USERPWD, $stashUsername . ":" . $stashPassword);
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
    }
}
