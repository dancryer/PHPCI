<?php

require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Registry.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Database.php');

class DatabaseTest extends \PHPUnit_Framework_TestCase
{
	protected $_host = 'localhost';
	protected $_user = 'b8_test';
	protected $_pass = 'b8_test';
	protected $_name = 'b8_test';

	public function testGetReadConnection()
	{
		\PHPCI\Framework\Database::setDetails($this->_name, $this->_user, $this->_pass);
		\PHPCI\Framework\Database::setReadServers(array($this->_host));

		$connection = \PHPCI\Framework\Database::getConnection('read');

		$this->assertInstanceOf('\PHPCI\Framework\Database', $connection);
	}

	public function testGetWriteConnection()
	{
		\PHPCI\Framework\Database::setDetails($this->_name, $this->_user, $this->_pass);
		\PHPCI\Framework\Database::setWriteServers(array($this->_host));

		$connection = \PHPCI\Framework\Database::getConnection('write');

		$this->assertInstanceOf('\PHPCI\Framework\Database', $connection);
	}

	public function testGetDetails()
	{
		\PHPCI\Framework\Database::setDetails($this->_name, $this->_user, $this->_pass);
		\PHPCI\Framework\Database::setReadServers(array('localhost'));

		$details = \PHPCI\Framework\Database::getConnection('read')->getDetails();
		$this->assertTrue(is_array($details));
		$this->assertTrue(($details['db'] == $this->_name));
		$this->assertTrue(($details['user'] == $this->_user));
		$this->assertTrue(($details['pass'] == $this->_pass));
	}

	/**
	 * @expectedException \Exception
	 */
	public function testConnectionFailure()
	{
		\PHPCI\Framework\Database::setDetails('non_existant', 'invalid_user', 'incorrect_password');
		\PHPCI\Framework\Database::setReadServers(array('localhost'));
		\PHPCI\Framework\Database::getConnection('read');
	}
}