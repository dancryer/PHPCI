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
    const MESSAGE_DEFAULT = 'Build %BUILD% has finished for commit <a href="%COMMIT_URI%">%SHORT_COMMIT%</a>
                            (%COMMIT_EMAIL%)> on branch <a href="%BRANCH_URI%">%BRANCH%</a>';

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
        if (!is_array($options) || !isset($options['api_key'])) {
            throw new \Exception('Please define the api_key for Flowdock Notify plugin!');
        }
        $this->api_key = trim($options['api_key']);
        $this->message = isset($options['message']) ? $options['message'] : self::MESSAGE_DEFAULT;
        $this->email = isset($options['email']) ? $options['email'] : 'PHPCI';
    }

    /**
     * Run the Flowdock plugin.
     * @return bool
     * @throws \Exception
     */
    public function execute()
    {

        $message = $this->phpci->interpolate($this->message);
        $successfulBuild = $this->build->isSuccessful() ? 'Success' : 'Failed';
        $push = new Push($this->api_key);
        $flowMessage = TeamInboxMessage::create()
            ->setSource("PHPCI")
            ->setFromAddress($this->email)
            ->setFromName($this->build->getProject()->getTitle())
            ->setSubject($successfulBuild)
            ->setTags(['#ci'])
            ->setLink($this->build->getBranchLink())
            ->setContent($message);

        if (!$push->sendTeamInboxMessage($flowMessage, array('connect_timeout' => 5000, 'timeout' => 5000))) {
            throw new \Exception(sprintf('Flowdock Failed: %s', $flowMessage->getResponseErrors()));
        }
        return true;
    }
}
