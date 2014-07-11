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
* XMPP Notification - Send notification for successful or failure build
* @author       Alexandre Russo <dev.github@ange7.com>
* @package      PHPCI
* @subpackage   Plugins
*/
class XMPP implements \PHPCI\Plugin
{
    protected $directory;
    protected $phpci;
    protected $build;

    /**
     * @var string, username of sender account xmpp
     */
    protected $username;

    /**
     * @var string, alias server of sender account xmpp
     */
    protected $server;

    /**
     * @var string, password of sender account xmpp
     */
    protected $password;

    /**
     * @var string, alias for sender
     */
    protected $alias;

    /**
     * @var string, use tls
     */
    protected $tls;

    /**
     * @var array, list of recipients xmpp accounts
     */
    protected $recipients;

    /**
     * @var string, mask to format date
     */
    protected $date_format;

    /**
     *
     * @param Builder $phpci
     * @param Build $build
     * @param array $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;

        $this->username    = '';
        $this->password    = '';
        $this->server      = '';
        $this->alias       = '';
        $this->recipients  = array();
        $this->tls         = false;
        $this->date_format = '%c';

        /*
         * Set recipients list
         */
        if (!empty($options['recipients'])) {
            if (is_string($options['recipients'])) {
                $this->recipients = array($options['recipients']);
            } elseif (is_array($options['recipients'])) {
                $this->recipients = $options['recipients'];
            }
        }

        $this->setOptions($options);
    }

    /**
     * Set options configuration for plugin
     *
     * @param array $options
     */
    protected function setOptions($options)
    {
        foreach (array('username', 'password', 'alias', 'tls', 'server', 'date_format') as $key) {
            if (array_key_exists($key, $options)) {
                $this->{$key} = $options[$key];
            }
        }
    }

    /**
     * Get config format for sendxmpp config file
     *
     * @return string
     */
    protected function getConfigFormat()
    {
        $conf = $this->username;
        if (!empty($this->server)) {
            $conf .= ';'.$this->server;
        }

        $conf .= ' '.$this->password;

        if (!empty($this->alias)) {
            $conf .= ' '.$this->alias;
        }

        return $conf;
    }

    /**
     * Find config file for sendxmpp binary (default is .sendxmpprc)
     */
    public function findConfigFile()
    {
        if (file_exists('.sendxmpprc')) {
            if (md5(file_get_contents('.sendxmpprc')) !== md5($this->getConfigFormat())) {
                return null;
            }

            return true;
        }

        return null;
    }

    /**
    * Send notification message.
    */
    public function execute()
    {
        $sendxmpp = $this->phpci->findBinary('/usr/bin/sendxmpp');

        if (!$sendxmpp) {
            $this->phpci->logFailure('Could not find sendxmpp.');
            return false;
        }

        /*
         * Without recipients we can't send notification
         */
        if (count($this->recipients) == 0) {
            return false;
        }

        /*
         * Try to build conf file
         */
        if (is_null($this->findConfigFile())) {
            file_put_contents('.sendxmpprc', $this->getConfigFormat());
            chmod('.sendxmpprc', 0600);
        }

        /*
         * Enabled ssl for connection
         */
        $tls = '';
        if ($this->tls) {
            $tls = ' -t';
        }

        $message_file = uniqid('xmppmessage');
        if ($this->buildMessage($message_file) === false) {
            return false;
        }

        /*
         * Send XMPP notification for all recipients
         */
        $cmd = $sendxmpp . "%s -f .sendxmpprc -m %s %s";
        $recipients = implode(' ', $this->recipients);

        $success = $this->phpci->executeCommand($cmd, $tls, $message_file, $recipients);

        print $this->phpci->getLastOutput();

        /*
         * Remove temp message file
         */
        $this->phpci->executeCommand("rm -rf ".$message_file);

        return $success;
    }

    /**
     * @param $message_file
     * @return int
     */
    protected function buildMessage($message_file)
    {
        if ($this->build->isSuccessful()) {
            $message = "✔ [".$this->build->getProjectTitle()."] Build #" . $this->build->getId()." successful";
        } else {
            $message = "✘ [".$this->build->getProjectTitle()."] Build #" . $this->build->getId()." failure";
        }

        $message .= ' ('.strftime($this->date_format).')';

        return file_put_contents($message_file, $message);
    }
}
