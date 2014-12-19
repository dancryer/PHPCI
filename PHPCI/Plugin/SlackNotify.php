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
    private $message;

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

        if (is_array($options) && isset($options['webhook_url'])) {
            $this->webHook = trim($options['webhook_url']);

            if (isset($options['message'])) {
                $this->message = $options['message'];
            } else {
                $this->message = '%PROJECT_TITLE% - <%BUILD_URI%|Build #%BUILD%> has finished building <%BUILD_URI%|%SHORT_COMMIT% (%COMMIT_EMAIL%)> on branch %BRANCH%';
            }

            if (isset($options['room'])) {
                $this->room = $options['room'];
            }

            if (isset($options['username'])) {
                $this->username = $options['username'];
            }

        } else {
            throw new \Exception('Please define the webhook_url for slack_notify plugin!');
        }
    }

    /**
     * Run the Slack plugin.
     * @return bool
     */
    public function execute()
    {
        $message = $this->phpci->interpolate($this->message);

        $successfulBuild = $this->build->isSuccessful();

        // $this->build->getCommitterEmail();
        // $this->build->getCommitMessage();

        $data = array(
            'attachments' => array(
                array(
                    'fallback' => $message,
                    'pretext' => $message,
                    'color' => $successfulBuild ? 'good' : 'danger',
                    'fields' => array(
                        array(
                            'title' => 'Results',
                            'value' => $successfulBuild ? 'Success' : 'Failure',
                            'short' => false
                        )
                    )
                )
            )
        );


        if (!empty($this->room))
        {
            $data['channel'] = '#' . $this->room;
        }

        if (!empty($this->username))
        {
            $data['username'] = $this->username;
        }

        $data = json_encode($data);

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

        $success = true;

        if ($response == false) {
            //throw new \Exception (curl_error($curlh), curl_errno($curlh)); // LOG AN ERROR
            $success = false;
        }

        if ($response_code != 200) {
            //throw new \Exception ('Slack failed ' - $response_code . ' - ' . $response); // Log an error
            $success = false;
        }

        return $success;
    }
}
