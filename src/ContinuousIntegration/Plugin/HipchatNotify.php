<?php
/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Kiboko\Component\ContinuousIntegration\Plugin;

use Kiboko\Component\ContinuousIntegration\Builder;
use Kiboko\Component\ContinuousIntegration\Helper\Lang;
use Kiboko\Component\ContinuousIntegration\Model\Build;

/**
 * Hipchat Plugin
 * @author       James Inman <james@jamesinman.co.uk>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class HipchatNotify implements \Kiboko\Component\ContinuousIntegration\Plugin
{
    protected $authToken;
    protected $color;
    protected $notify;

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
        $hipChat = new \HipChat\HipChat($this->authToken);
        $message = $this->phpci->interpolate($this->message);

        $result = true;
        if (is_array($this->room)) {
            foreach ($this->room as $room) {
                if (!$hipChat->message_room($room, 'PHPCI', $message, $this->notify, $this->color)) {
                    $result = false;
                }
            }
        } else {
            if (!$hipChat->message_room($this->room, 'PHPCI', $message, $this->notify, $this->color)) {
                $result = false;
            }
        }

        return $result;
    }
}
