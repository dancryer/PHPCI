<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2013, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         http://www.phptesting.org/
 */

namespace PHPCI\Controller;

use b8;
use b8\Store;
use PHPCI\Model\Build;

/**
 * Api Controller - give informations to other apps.
 * @author       André Cianfarani <acianfa@gmail.com>
 * @package      PHPCI
 * @subpackage   Web
 */
class ApiController extends \PHPCI\Controller
{
    private $wsseHeader;
    private $lifetime = 300;
    private $wsseHeaderInfo;

	public function init()
	{
        $this->_projectStore      = Store\Factory::getStore('Project');

        $this->wsseHeader = $this->getWsseHeader();
        $this->wsseHeaderInfo = $this->parseHeader();

        $user = b8\Store\Factory::getStore('User')->getWhere(array('email' => $this->wsseHeaderInfo["Username"]));

        if( 0 == count($user["items"])) {
            throw new \Exception("Invalid email given for Wsse auth");
        } else {
            $user = $user["items"][0];
        }

        $this->wsseAuth($user);
	}


	/**
	 * Called by other apps:
	 */
	public function projects()
	{
		$this -> checkMethod(array("GET"));

		$projects = $this->_projectStore->getWhere(array(), null, null, array(), array('title' => 'ASC'));
		$res = array();
		foreach ($projects["items"] as $project) {
			$entry = array("id"=>$project->getId(), "title"=>$project->getTitle(), "type"=>$project->getType(), "access_information" => $this->getUrl($project));
			$res[] = $entry;
		}
		echo json_encode($res);
		die();
	}

	protected function checkMethod($allowed) {
		$method = $this->request->getMethod();
		if (! in_array($method, $allowed)) {
			die($method.' bad method according action to perform');
		}
	}
	protected function getUrl($project) {
		$buildBase = new Build();
		$buildBase->setProjectId($project->getId());
		$buildFactory = \PHPCI\BuildFactory::getBuild($buildBase);

		if ($project->getType() == "local") {
			return $this->getProject()->getReference();
		}
		
		return $buildFactory -> getCloneUrl();
	}

    /**
     * PRIVATE FUNCTIONS
     */
    private function wsseAuth($user)
    {

        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $timestamp = (string)$now->format('Y-m-d\TH:i:s\Z');

        $nonce = $this->buildNonce();
        $digest = $this->buildDigest($nonce, $timestamp, $this->encodeSecret($user->getApiKey()));

        $exceptionMsg = "Wsse auth failed";

        if ($this->wsseHeaderInfo["Username"] != $user->getEmail()) {

            throw new \Exception($exceptionMsg." emails mismatch");
        }
        if ($this->wsseHeaderInfo["PasswordDigest"] != $digest) {

            throw new \Exception($exceptionMsg." digests mismatch");
        }
        if ( $this->wsseHeaderInfo["Nonce"] !=  $nonce) {

            throw new \Exception($exceptionMsg." nonces mismatch");
        }
        if ($this->wsseHeaderInfo["Created"] != $timestamp) {

            throw new \Exception($exceptionMsg." created mismatch");
        }
    }

    private function getWsseHeader()
    {
        $wsseHeaderKey = '/HTTP_X_WSSE/';
        foreach ($_SERVER as $key => $val) {
            if( preg_match($wsseHeaderKey, $key) ) {
                return $val;
            }
        }
        return false;
    }

    private function parseValue($key)
    {
        if(!preg_match('/'.$key.'="([^"]+)"/', $this->wsseHeader, $matches))
        {
            throw new \Exception('The string was not found');
        }

        return $matches[1];
    }

    private function parseHeader()
    {
        $result = array();

        try
        {
            $result['Username'] = $this->parseValue('Username');
            $result['PasswordDigest'] = $this->parseValue('PasswordDigest');
            $result['Nonce'] = $this->parseValue('Nonce');
            $result['Created'] = $this->parseValue('Created');
        }
        catch(\Exception $e)
        {
            return false;
        }

        return $result;
    }

    protected function validateDigest($user, $digest, $nonce, $created, $secret)
    {
        //expire timestamp after specified lifetime
        if(time() - strtotime($created) > $this->lifetime)
        {
            throw new \Exception('Wsse auth Token has expired.');
        }
        $expected = base64_encode( sha1( $nonce . $created . $secret ) );

        return $digest === $expected;
    }

    private function buildNonce()
    {
        return substr( base64_encode( sha1( time() . 'salt' ) ), 0, 16 );
    }

    private function buildDigest($nonce, $created, $secret)
    {
        return base64_encode( sha1( $nonce . $created . $secret ) );
    }

    private function encodeSecret($secret)
    {
        return bin2hex(hash("sha512", $secret, true));
    }
}
