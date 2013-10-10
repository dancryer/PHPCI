<?php

namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;

/**
 * IRC Plugin - Sends a notification to an IRC channel
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Irc implements \PHPCI\Plugin
{
    private $phpci;
    private $message;
    private $server;
    private $port;
    private $room;
    private $nick;

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->message = $options['message'];

        $buildSettings = $phpci->getConfig('build_settings');


        if (isset($buildSettings['irc'])) {
            $irc = $buildSettings['irc'];

            $this->server = $irc['server'];
            $this->port = $irc['port'];
            $this->room = $irc['room'];
            $this->nick = $irc['nick'];
        }
    }

    public function execute()
    {
        $msg = $this->phpci->interpolate($this->message);

        if (empty($this->server) || empty($this->room) || empty($this->nick)) {
            $this->phpci->logFailure('You must configure a server, room and nick.');
        }

        if (empty($this->port)) {
            $this->port = 6667;
        }

        $sock = fsockopen($this->server, $this->port);
        fputs($sock, 'USER ' . $this->nick . ' phptesting.org ' . $this->nick . ' :' . $this->nick . "\r\n");
        fputs($sock, 'NICK ' . $this->nick . "\r\n");
        fputs($sock, 'PRIVMSG ' . $this->room . ' :' . $msg . "\r\n");

        while ($res = fgets($sock)) {
            // We don't need to do anything,
            // but the IRC server doesn't appear to post the message
            // unless we wait for responses.
        }

        fclose($sock);

        return true;
    }
}
