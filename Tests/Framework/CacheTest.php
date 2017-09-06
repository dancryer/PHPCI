<?php

require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Registry.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Cache.php');

use PHPCI\Framework\Registry,
    PHPCI\Framework\Cache;

class CacheTest extends PHPUnit_Framework_TestCase
{
	public function testCreateSingleton()
	{
		$cache = Cache::getInstance();
		$this->assertTrue($cache instanceof Cache);
	}

	public function testDisableCaching()
	{
		Registry::getInstance()->set('DisableCaching', true);

		$cache = Cache::getInstance();
		$this->assertFalse($cache->isEnabled());
		$this->assertFalse($cache->set('anything', 10));
		$this->assertTrue(is_null($cache->get('anything')));

		Registry::getInstance()->set('DisableCaching', false);
	}

	public function testCaching()
	{
		$cache = Cache::getInstance();

		if($cache->isEnabled())
		{
			$this->assertTrue($cache->set('anything', 10));
			$this->assertTrue($cache->get('anything') == 10);
			$this->assertTrue(is_null($cache->get('invalid')));
		}
	}
}
