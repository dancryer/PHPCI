<?php
/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Kiboko\Component\ContinuousIntegration\Plugin;

use b8\HttpClient;
use Kiboko\Component\ContinuousIntegration\Builder;
use Kiboko\Component\ContinuousIntegration\Model\Build;

/**
* Integrates Kiboko CI with Deployer: https://github.com/rebelinblue/deployer
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class Deployer implements \Kiboko\Component\ContinuousIntegration\Plugin
{
    protected $webhookUrl;
    protected $reason;
    protected $updateOnly;

    /**
     * Set up the plugin, configure options, etc.
     * @param Builder $phpci
     * @param Build $build
     * @param array $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;
        $this->reason = 'PHPCI Build #%BUILD% - %COMMIT_MESSAGE%';

        if (isset($options['webhook_url'])) {
            $this->webhookUrl = $options['webhook_url'];
        }

        if (isset($options['reason'])) {
            $this->reason = $options['reason'];
        }
        
        $this->updateOnly = isset($options['update_only']) ? (bool) $options['update_only'] : true;
    }

    /**
    * Copies files from the root of the build directory into the target folder
    */
    public function execute()
    {
        if (empty($this->webhookUrl)) {
            $this->phpci->logFailure('You must specify a webhook URL.');
            return false;
        }

        $http = new HttpClient();

        $response = $http->post($this->webhookUrl, array(
            'reason' => $this->phpci->interpolate($this->reason),
            'source' => 'PHPCI',
            'url' => $this->phpci->interpolate('%BUILD_URI%'),
            'branch' => $this->phpci->interpolate('%BRANCH%'),
            'commit' =>  $this->phpci->interpolate('%COMMIT%'),
            'update_only' => $this->updateOnly,
        ));

        return $response['success'];
    }
}
