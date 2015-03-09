<?php
/**
 * PHPCI - Continuous Integration for PHP
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
    private $icon;

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
                $this->message = '<%PROJECT_URI%|%PROJECT_TITLE%> - <%BUILD_URI%|Build #%BUILD%> has finished ';
                $this->message .= 'for commit <%COMMIT_URI%|%SHORT_COMMIT% (%COMMIT_EMAIL%)> ';
                $this->message .= 'on branch <%BRANCH_URI%|%BRANCH%>';
            }

            if (isset($options['room'])) {
                $this->room = $options['room'];
            } else {
                $this->room = '#phpci';
            }

            if (isset($options['username'])) {
                $this->username = $options['username'];
            } else {
                $this->username = 'PHPCI';
            }

            if (isset($options['icon'])) {
                $this->icon = $options['icon'];
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

        if ($successfulBuild) {
            $status = 'Success';
            $color = 'good';
        } else {
            $status = 'Failed';
            $color = 'danger';
        }

        // Build up the attachment data
        $attachment = new \Maknz\Slack\Attachment(array(
            'fallback' => $message,
            'pretext'  => $message,
            'color'    => $color,
            'fields'   => array(
                new \Maknz\Slack\AttachmentField(array(
                    'title' => 'Status',
                    'value' => $status,
                    'short' => false
                ))
            )
        ));

        $client = new \Maknz\Slack\Client($this->webHook);

        $message = $client->createMessage();

        if (!empty($this->room)) {
            $message->setChannel($this->room);
        }

        if (!empty($this->username)) {
            $message->setUsername($this->username);
        }

        if (!empty($this->icon)) {
            $message->setIcon($this->icon);
        }

        $message->attach($attachment);

        $success = true;

        $message->send('');

        return $success;
    }
}
