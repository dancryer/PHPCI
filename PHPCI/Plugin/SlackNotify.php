<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;

/**
 * Slack Plugin
 * @author       Stephen Ball <phpci@stephen.rebelinblue.com>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class SlackNotify implements \PHPCI\Plugin
{
    private $webHook;
    private $room;
    private $username;

    /**
     * Set up the plugin, configure options, etc.
     * @param Builder $phpci
     * @param Build $build
     * @param array $options
     * @throws \Exception
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;

        if (is_array($options) && isset($options['webhook_url']) && isset($options['room'])) {
            $this->webHook = $options['webhook_url'];
            $this->room = $options['room'];

            if (isset($options['message'])) {
                $this->message = $options['message'];
            } else {
                $this->message = '%PROJECT_TITLE% (%COMMIT%) built at %BUILD_URI%';
            }

            if (isset($options['username'])) {
                $this->username = $options['username'];
            } else {
                $this->username = 'PHPCI';
            }


        } else {
            throw new \Exception('Please define room and webhook_url for slack_notify plugin!');
        }
    }

    /**
     * Run the Slack plugin.
     * @return bool
     */
    public function execute()
    {
        $message = $this->phpci->interpolate($this->message);

        $data = json_encode(array(
            'text'          => $message,
            'channel'       =>  '#' . $this->room,
            'username'      =>  $this->username
        ));

        $curlh = curl_init();
        curl_setopt($curlh, CURLOPT_URL, $this->webHook);
        curl_setopt($curlh, CURLOPT_POST, 1);
        curl_setopt($curlh, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curlh, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlh, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data) 
        ));

        $response = curl_exec($curlh);
        $response_code = curl_getinfo($curlh, CURLINFO_HTTP_CODE);

        if ($response == false) {
            throw new \Exception (curl_error($curlh), curl_errno($curlh));
        }

        if ($response_code != 200) {
            // Fall back to good 'ol email
            throw new \Exception ('Slack failed ' - $response_code . ' - ' . $response);
        }

        return true;
    }
}
