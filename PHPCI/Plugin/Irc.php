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
 * IRC Plugin - Sends a notification to an IRC channel
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Irc implements \PHPCI\Plugin
{
    protected $phpci;
    protected $build;
    protected $message;
    protected $server;
    protected $port;
    protected $room;
    protected $nick;

    /**
     * Standard Constructor
     *
     * $options['directory'] Output Directory. Default: %BUILDPATH%
     * $options['filename']  Phar Filename. Default: build.phar
     * $options['regexp']    Regular Expression Filename Capture. Default: /\.php$/
     * $options['stub']      Stub Content. No Default Value
     *
     * @param Builder $phpci
     * @param Build   $build
     * @param array   $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;
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

    /**
     * Run IRC plugin.
     * @return bool
     */
    public function execute()
    {
        $msg = $this->phpci->interpolate($this->message);

        if (empty($this->server) || empty($this->room) || empty($this->nick)) {
            $this->phpci->logFailure(Lang::get('irc_settings'));
        }

        if (empty($this->port)) {
            $this->port = 6667;
        }

        $sock = fsockopen($this->server, $this->port);
        fputs($sock, 'USER ' . $this->nick . ' phptesting.org ' . $this->nick . ' :' . $this->nick . "\r\n");
        fputs($sock, 'NICK ' . $this->nick . "\r\n");
        fputs($sock, 'JOIN ' . $this->room . "\r\n");
        fputs($sock, 'PRIVMSG ' . $this->room . ' :' . $msg . "\r\n");

        while (fgets($sock)) {
            // We don't need to do anything,
            // but the IRC server doesn't appear to post the message
            // unless we wait for responses.
        }

        fclose($sock);

        return true;
    }
}
