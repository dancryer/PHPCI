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
 * Campfire Plugin - Allows Campfire API actions.
 * strongly based on icecube (http://labs.mimmin.com/icecube)
 * @author       André Cianfarani <acianfa@gmail.com>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Campfire implements \PHPCI\Plugin
{
    private $url;
    private $authToken;
    private $userAgent;
    private $cookie;
    private $verbose;
    private $roomId;

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;

        $this->message = $options['message'];
        $this->userAgent = "PHPCI/1.0 (+http://www.phptesting.org/)";
        $this->cookie = "phpcicookie";

        $buildSettings = $phpci->getConfig('build_settings');
        if (isset($buildSettings['campfire'])) {
            $campfire = $buildSettings['campfire'];
            $this->url = $campfire['url'];
            $this->authToken = $campfire['authToken'];
            $this->roomId = $campfire['roomId'];
        } else {
            throw new \Exception("No connection parameters given for Campfire plugin");
        }

    }

    public function execute()
    {
        $url = PHPCI_URL."build/view/".$this->build->getId();
        $message = str_replace("%buildurl%", $url, $this->message);
        $this->joinRoom($this->roomId);
        $status = $this->speak($message, $this->roomId);
        $this->leaveRoom($this->roomId);

        return $status;

    }

    public function joinRoom($roomId)
    {
        $this->getPageByPost('/room/'.$roomId.'/join.json');
    }

    public function leaveRoom($roomId)
    {
        $this->getPageByPost('/room/'.$roomId.'/leave.json');
    }

    public function speak($message, $roomId, $isPaste = false)
    {
        $page = '/room/'.$roomId.'/speak.json';
        if ($isPaste) {
            $type = 'PasteMessage';
        } else {
            $type = 'TextMessage';
        }

        return $this->getPageByPost($page, array('message' => array('type' => $type, 'body' => $message)));

    }

    private function getPageByPost($page, $data = null)
    {
        $url = $this->url . $page;
        // The new API allows JSON, so we can pass
        // PHP data structures instead of old school POST
        $json = json_encode($data);

        // cURL init & config
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($handle, CURLOPT_VERBOSE, $this->verbose);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($handle, CURLOPT_USERPWD, $this->authToken . ':x');
        curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($handle, CURLOPT_COOKIEFILE, $this->cookie);

        curl_setopt($handle, CURLOPT_POSTFIELDS, $json);
        $output = curl_exec($handle);

        curl_close($handle);

        // We tend to get one space with an otherwise blank response
        $output = trim($output);
        if (strlen($output)) {
            /* Responses are JSON. Decode it to a data structure */
            return json_decode($output);
        }
        // Simple 200 OK response (such as for joining a room)
        // TODO: check for other result codes here
        return true;
    }
}
