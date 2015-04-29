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
use Mremi\Flowdock\Api\Push\Push;
use Mremi\Flowdock\Api\Push\TeamInboxMessage;

/**
 * Flowdock Plugin
 * @author       Petr Cervenka <petr@nanosolutions.io>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class FlowdockNotify implements \PHPCI\Plugin
{
    private $api_key;
    private $email;

    /**
     * Set up the plugin, configure options, etc.
     * @param Builder $phpci
     * @param Build   $build
     * @param array   $options
     * @throws \Exception
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;

        if (is_array($options) && isset($options['api_key'])) {
            $this->api_key = trim($options['api_key']);

            if (isset($options['message'])) {
                $this->message = $options['message'];
            } else {
                $this->message = 'Build %BUILD% has finished ';
                $this->message .= 'for commit <a href="%COMMIT_URI%">%SHORT_COMMIT%</a> (%COMMIT_EMAIL%)> ';
                $this->message .= 'on branch <a href="%BRANCH_URI%">%BRANCH%</a>';
            }

            if (isset($options['email'])) {
                $this->email = $options['email'];
            } else {
                $this->email = 'PHPCI';
            }

        } else {
            throw new \Exception('Please define the api_key for flowdock_notify plugin!');
        }
    }

    /**
     * Run the Flowdock plugin.
     * @return bool
     * @throws \Exception
     */
    public function execute()
    {

        $message = $this->phpci->interpolate($this->message);

        $successfulBuild = $this->build->isSuccessful();

        if ($successfulBuild) {
            $status = 'Success';
        } else {
            $status = 'Failed';
        }

        $push = new Push($this->api_key);

        $flowMessage = TeamInboxMessage::create()
            ->setSource("PHPCI")
            ->setFromAddress($this->email)
            ->setFromName($this->build->getProject()->getTitle())
            ->setSubject($status)
            ->setTags(['#ci'])
            ->setLink($this->build->getBranchLink())
            ->setContent($message);

        if (!$push->sendTeamInboxMessage($flowMessage, array('connect_timeout' => 5000, 'timeout' => 5000))) {
            // handle errors...
            throw new \Exception('Flowdock Failed :'.$flowMessage->getResponseErrors());
        }

        return true;
    }
}
