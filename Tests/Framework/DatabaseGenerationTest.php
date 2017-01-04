<?php

require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Registry.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Model.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Database.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Database/Map.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Database/Generator.php');

use PHPCI\Framework\Database\Generator,
    PHPCI\Framework\Database\Map;

class DatabaseGenerationTest extends \PHPUnit_Framework_TestCase
{
	protected $_host = 'localhost';
	protected $_user = 'b8_test';
	protected $_pass = 'b8_test';
	protected $_name = 'b8_test';
	protected $_db;

	public function setUp()
	{
		\PHPCI\Framework\Database::setDetails($this->_name, $this->_user, $this->_pass);
		\PHPCI\Framework\Database::setWriteServers(array($this->_host));

		$this->_db = \PHPCI\Framework\Database::getConnection('write');

		$this->_db->query('DROP TABLE IF EXISTS tres');
		$this->_db->query('DROP TABLE IF EXISTS dos');
		$this->_db->query('DROP TABLE IF EXISTS uno');
	}

	public function testCreateDatabase()
	{
		$gen = new Generator($this->_db, 'Test', dirname(__FILE__) . '/data/generation/models/');
		$gen->generate();

		$map    = new Map($this->_db);
		$t      = $map->generate();

		$this->assertTrue(array_key_exists('uno', $t));
		$this->assertTrue(array_key_exists('dos', $t));
		$this->assertTrue(array_key_exists('tres', $t));
		$this->assertFalse(array_key_exists('bad_table', $t));
		$this->assertTrue(count($t['uno']['indexes']) == 1);
		$this->assertTrue(count($t['dos']['indexes']) == 3);
		$this->assertTrue(count($t['tres']['indexes']) == 2);
		$this->assertTrue(count($t['uno']['columns']) == 11);
		$this->assertTrue(count($t['dos']['columns']) == 4);
		$this->assertTrue(count($t['tres']['columns']) == 6);
		$this->assertTrue(array_key_exists('PRIMARY', $t['uno']['indexes']));
		$this->assertTrue(array_key_exists('PRIMARY', $t['dos']['indexes']));
		$this->assertFalse(array_key_exists('PRIMARY', $t['tres']['indexes']));
	}

	public function testUpdateDatabase()
	{
		$gen = new Generator($this->_db, 'Test', dirname(__FILE__) . '/data/generation/models/');
		$gen->generate();

		$gen = new Generator($this->_db, 'Update', dirname(__FILE__) . '/data/generation/update_models/');
		$gen->generate();

		$map    = new Map($this->_db);
		$t      = $map->generate();

		$this->assertTrue(array_key_exists('uno', $t));
		$this->assertTrue(array_key_exists('dos', $t));
		$this->assertTrue(array_key_exists('tres', $t));
		$this->assertFalse(array_key_exists('bad_table', $t));
		$this->assertTrue(count($t['uno']['indexes']) == 1);
		$this->assertTrue(count($t['dos']['indexes']) == 3);
		$this->assertTrue(count($t['tres']['indexes']) == 3);
		$this->assertTrue(count($t['uno']['columns']) == 10);
		$this->assertTrue(count($t['dos']['columns']) == 4);
		$this->assertTrue(count($t['tres']['columns']) == 10);
		$this->assertTrue(array_key_exists('PRIMARY', $t['uno']['indexes']));
		$this->assertTrue(array_key_exists('PRIMARY', $t['dos']['indexes']));
		$this->assertTrue(array_key_exists('PRIMARY', $t['tres']['indexes']));
	}
}
