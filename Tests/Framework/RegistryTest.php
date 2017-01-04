<?php

require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Registry.php');
use PHPCI\Framework\Registry;

class RegistryTest extends \PHPUnit_Framework_TestCase
{
	public function testSingleton()
	{
		Registry::forceReset();
		$instance   = Registry::getInstance();
		$instance->set('test', true);

		$instance2  = Registry::getInstance();
		$this->assertTrue($instance2->get('test', false));
	}

	public function testStoreAndRetrieve()
	{
		Registry::forceReset();
		$r = Registry::getInstance();
		$r->set('test', 'cat');

		$this->assertTrue($r->get('test', 'dog') == 'cat');
	}

	public function testSetArray()
	{
		Registry::forceReset();
		$r = Registry::getInstance();
		$r->set('one', 'two');
		$r->setArray(array('test' => 'cat'));

		$this->assertTrue($r->get('test', 'dog') == 'cat');
		$this->assertTrue($r->get('one', 'three') == 'two');
	}

	public function testGetNonExistent()
	{
		Registry::forceReset();
		$r = Registry::getInstance();
		$this->assertTrue(!$r->get('cat', false));
	}

	public function testGetParams()
	{
		Registry::forceReset();

		$_REQUEST                   = array();
		$_REQUEST['cat']            = 'dog';
		$_SERVER['REQUEST_METHOD']  = 'GET';

		$r      = Registry::getInstance();
		$params = $r->getParams();
		$this->assertTrue(is_array($params));
		$this->assertArrayHasKey('cat', $params);
		$this->assertArrayNotHasKey('dog', $params);
	}

	public function testEmptyInput()
	{
		Registry::forceReset();
		$r      = Registry::getInstance();
		$params = $r->getParams();

		$this->assertTrue(is_array($params));
		$this->assertTrue(!count($params));
	}

	public function testGetSetUnsetParam()
	{
		Registry::forceReset();
		$r = Registry::getInstance();
		$this->assertTrue($r->getParam('cat', false) == false);
		$r->setParam('cat', 'dog');

		$this->assertTrue($r->getParam('cat', false) == 'dog');

		$r->unsetParam('cat');
		$this->assertTrue($r->getParam('cat', false) == false);
	}
}