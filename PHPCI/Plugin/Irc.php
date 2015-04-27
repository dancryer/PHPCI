<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI\Helper\Lang;

/**
 * IRC Plugin - Sends a notification to an IRC channel
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Irc extends AbstractInterpolatingPlugin
{
    protected $message;
    protected $server;
    protected $port;
    protected $room;
    protected $nick;

    /**
     * Configure the plugin.
     *
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        $this->message = $options['message'];
    }

    /**
     * {@inheritdoc}
     */
    protected function setCommonSettings(array $settings)
    {
        parent::setCommonSettings($settings);

        if (!isset($settings['server'], $settings['room'], $settings['nick'])) {
            throw new \Exception(Lang::get('irc_settings'));
        }

        $this->server = $settings['server'];
        $this->port = isset($settings['port']) ? $settings['port'] : 6667;
        $this->room = $settings['room'];
        $this->nick = $settings['nick'];
    }

    /**
     * Run IRC plugin.
     * @return bool
     */
    public function execute()
    {
        $msg = $this->interpolator->interpolate($this->message);

        $sock = fsockopen($this->server, $this->port);
        stream_set_timeout($sock, 1);

        $connectCommands = array(
            'USER ' . $this->nick . ' 0 * :' . $this->nick,
            'NICK ' . $this->nick,
        );
        $this->executeIrcCommands($sock, $connectCommands);
        $this->executeIrcCommand($sock, 'JOIN ' . $this->room);
        $this->executeIrcCommand($sock, 'PRIVMSG ' . $this->room . ' :' . $msg);

        fclose($sock);

        return true;
    }

    /**
     * @param resource $socket
     * @param array $commands
     * @return bool
     */
    private function executeIrcCommands($socket, array $commands)
    {
        foreach ($commands as $command) {
            fputs($socket, $command . "\n");
        }

        $pingBack = false;

        // almost all servers expect pingback!
        while ($response = fgets($socket)) {
            $matches = array();
            if (preg_match('/^PING \\:([A-Z0-9]+)/', $response, $matches)) {
                $pingBack = $matches[1];
            }
        }

        if ($pingBack) {
            $command = 'PONG :' . $pingBack . "\n";
            fputs($socket, $command);
        }
    }

    /**
     *
     * @param resource $socket
     * @param string $command
     * @return bool
     */
    private function executeIrcCommand($socket, $command)
    {
        return $this->executeIrcCommands($socket, array($command));
    }
}
