<?php

require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Registry.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/HttpClient.php');

use PHPCI\Framework\Registry,
    PHPCI\Framework\HttpClient;

class HttpClientTest extends \PHPUnit_Framework_TestCase
{
	public function testSimpleRequest()
	{
		$http = new HttpClient();
		$html = $http->request('GET', 'https://www.cloudflare.com/');

		$this->assertContains('CloudFlare', $html['body']);
	}

	public function testBaseUrl()
	{
		$http = new HttpClient('https://www.cloudflare.com');
		$html = $http->request('GET', '/');

		$this->assertContains('CloudFlare', $html['body']);
	}

	public function testGet()
	{
		$http = new HttpClient('https://www.cloudflare.com');
		$html = $http->get('overview', array('x' => 1));

		$this->assertContains('CloudFlare', $html['body']);
	}

	public function testGetJson()
	{
		$http = new HttpClient('http://echo.jsontest.com');
		$data = $http->get('/key/value');

		$this->assertArrayHasKey('key', $data['body']);
	}

	public function testPost()
	{
		$http = new HttpClient('http://echo.jsontest.com');
		$data = $http->post('/key/value', array('test' => 'x'));

		$this->assertTrue(is_array($data));
	}

	public function testPut()
	{
		$http = new HttpClient('http://echo.jsontest.com');
		$data = $http->put('/key/value', array('test' => 'x'));

		$this->assertTrue(is_array($data));
	}

	public function testDelete()
	{
		$http = new HttpClient('http://echo.jsontest.com');
		$data = $http->delete('/key/value', array('test' => 'x'));

		$this->assertTrue(is_array($data));
	}
}