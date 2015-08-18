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
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;

/**
 * Hipchat Plugin
 * @author       James Inman <james@jamesinman.co.uk>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class HipchatNotify implements \PHPCI\Plugin
{
    protected $authToken;
    protected $color;
    protected $notify;
    protected $v2Api;

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

        $this->userAgent = "PHPCI/1.0 (+http://www.phptesting.org/)";
        $this->cookie = "phpcicookie";

        if (is_array($options) && isset($options['authToken']) && isset($options['room'])) {
            $this->authToken = $options['authToken'];
            $this->room = $options['room'];

            if (isset($options['message'])) {
                $this->message = $options['message'];
            } else {
                $this->message = Lang::get('x_built_at_x');
            }

            if (isset($options['color'])) {
                $this->color = $options['color'];
            } else {
                $this->color = 'yellow';
            }

            if (isset($options['notify'])) {
                $this->notify = $options['notify'];
            } else {
                $this->notify = false;
            }

            if (isset($options['v2Api'])) {
                $this->v2Api = true;
            } else {
                $this->v2Api = false;
            }
        } else {
            throw new \Exception(Lang::get('hipchat_settings'));
        }

    }

    /**
     * Run the HipChat plugin.
     * @return bool
     */
    public function execute()
    {
        $message = $this->phpci->interpolate($this->message);

        $result = true;
        if (is_array($this->room)) {
            foreach ($this->room as $room) {
		            if (!$this->sendHipchatMessage($room, $message)) {
                    $result = false;
                }
            }
        } else {
            if (!$this->sendHipChatMessage($this->room, $message)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Send the message to Hipchat
     *
     * @param string $room_id
     * @param string $msg
     * @return bool
     */
    private function sendHipchatMessage($room_id, $msg)
    {
        if (! $this->v2Api) {
            $hipChat = new \HipChat\HipChat($this->authToken);
            return $hipChat->message_room($room, 'PHPCI', $message, $this->notify, $this->color);
        } else {
            $result = true;
            try {
              $auth = new \GorkaLaucirica\HipchatAPIv2Client\Auth\OAuth2($this->authToken);
              $client = new \GorkaLaucirica\HipchatAPIv2Client\Client($auth);
              $room = new \GorkaLaucirica\HipchatAPIv2Client\API\RoomAPI($client);
              $message = new \GorkaLaucirica\HipchatAPIv2Client\Model\Message();

              $message->setColor($this->color);
              $message->setMessage($msg);
              $message->setNotify($this->notify);

              $room->sendRoomNotification($room_id, $message);
            } catch (Exception $e) {
              $return = false;
            }

            return $result;
       }
    }
}
