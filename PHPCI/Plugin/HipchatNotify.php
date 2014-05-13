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
 * Hipchat Plugin
 * @author       James Inman <james@jamesinman.co.uk>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class HipchatNotify implements \PHPCI\Plugin
{
    private $authToken;
    private $userAgent;
    private $cookie;

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;

        $this->userAgent = "PHPCI/1.0 (+http://www.phptesting.org/)";
        $this->cookie = "phpcicookie";

        if (is_array($options) && isset($options['authToken']) && isset($options['room'])) {
            $this->authToken = $options['authToken'];
            $this->room = $options['room'];

            if (isset($options['message'])) {
                $this->message = $options['message'];
            } else {
                $this->message = '%PROJECT_TITLE% built at %BUILD_URI%';
            }
        } else {
            throw new \Exception('Please define room and authToken for hipchat_notify plugin!');
        }

    }

    public function execute()
    {
        $hipChat = new \HipChat\HipChat($this->authToken);
        $message = $this->phpci->interpolate($this->message);

        if (is_array($this->room)) {
            foreach ($this->room as $room) {
                $hipChat->message_room($room, 'PHPCI', $message);
            }
        } else {
            $hipChat->message_room($this->room, 'PHPCI', $message);
        }
    }
}
