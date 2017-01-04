<?php

require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Registry.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Model.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Controller.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Controller/RestController.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Store.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Store/Factory.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Database.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/View.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/View/UserView.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Database/Map.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Database/Generator.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Database/CodeGenerator.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Exception/HttpException.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Exception/HttpException/ValidationException.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Exception/HttpException/BadRequestException.php');
require_once(dirname(__FILE__) . '/../../PHPCI/Framework/Exception/HttpException/ForbiddenException.php');

use PHPCI;
use PHPCI\Framework\Database\Generator,
    PHPCI\Framework\Database\CodeGenerator,
    PHPCI\Framework\Database,
    PHPCI\Framework\Registry;

class CodeGenerationTest extends \PHPUnit_Framework_TestCase
{
	protected static $_db;
	protected static $_base;

	public static function setUpBeforeClass()
	{
		Database::setDetails('b8_test_' . getenv('PHPCI_BUILD'), 'b8_test', 'b8_test');
		Database::setWriteServers(array('localhost'));
		Database::setReadServers(array('localhost'));

		Registry::getInstance()->set('b8.app.namespace', 'Generation');

		self::$_db = Database::getConnection('write');

		self::$_db->query('DROP TABLE IF EXISTS tres');
		self::$_db->query('DROP TABLE IF EXISTS dos');
		self::$_db->query('DROP TABLE IF EXISTS uno');

		self::$_base = dirname(__FILE__) . '/data/generation/';
		$gen = new Generator(self::$_db, 'Test', self::$_base .'models/');
		$gen->generate();
	}

	public static function tearDownAfterClass()
	{
		self::$_db->query('DROP TABLE IF EXISTS tres');
		self::$_db->query('DROP TABLE IF EXISTS dos');
		self::$_db->query('DROP TABLE IF EXISTS uno');

		unlink(self::$_base . 'Generation/Model/Base/UnoBase.php');
		unlink(self::$_base . 'Generation/Model/Base/DosBase.php');
		unlink(self::$_base . 'Generation/Model/Base/TresBase.php');
		unlink(self::$_base . 'Generation/Store/Base/UnoStoreBase.php');
		unlink(self::$_base . 'Generation/Store/Base/DosStoreBase.php');
		unlink(self::$_base . 'Generation/Store/Base/TresStoreBase.php');
		unlink(self::$_base . 'Generation/Controller/Base/UnoControllerBase.php');
		unlink(self::$_base . 'Generation/Controller/Base/DosControllerBase.php');
		unlink(self::$_base . 'Generation/Controller/Base/TresControllerBase.php');
		unlink(self::$_base . 'Generation/Model/Uno.php');
		unlink(self::$_base . 'Generation/Model/Dos.php');
		unlink(self::$_base . 'Generation/Model/Tres.php');
		unlink(self::$_base . 'Generation/Store/UnoStore.php');
		unlink(self::$_base . 'Generation/Store/DosStore.php');
		unlink(self::$_base . 'Generation/Store/TresStore.php');
		unlink(self::$_base . 'Generation/Controller/UnoController.php');
		unlink(self::$_base . 'Generation/Controller/DosController.php');
		unlink(self::$_base . 'Generation/Controller/TresController.php');
	}

	public function testGenerate()
	{
		error_reporting(E_ALL);
		$codeGenerator = new CodeGenerator(self::$_db, 'Generation', self::$_base . 'Generation/');
		$codeGenerator->generateModels();
		$codeGenerator->generateStores();
		$codeGenerator->generateControllers();

		$this->assertFileExists(self::$_base . 'Generation/Model/Base/UnoBase.php');
		$this->assertFileExists(self::$_base . 'Generation/Model/Base/DosBase.php');
		$this->assertFileExists(self::$_base . 'Generation/Model/Base/TresBase.php');
		$this->assertFileExists(self::$_base . 'Generation/Store/Base/UnoStoreBase.php');
		$this->assertFileExists(self::$_base . 'Generation/Store/Base/DosStoreBase.php');
		$this->assertFileExists(self::$_base . 'Generation/Store/Base/TresStoreBase.php');
		$this->assertFileExists(self::$_base . 'Generation/Controller/Base/UnoControllerBase.php');
		$this->assertFileExists(self::$_base . 'Generation/Controller/Base/DosControllerBase.php');
		$this->assertFileExists(self::$_base . 'Generation/Controller/Base/TresControllerBase.php');
		$this->assertFileExists(self::$_base . 'Generation/Model/Uno.php');
		$this->assertFileExists(self::$_base . 'Generation/Model/Dos.php');
		$this->assertFileExists(self::$_base . 'Generation/Model/Tres.php');
		$this->assertFileExists(self::$_base . 'Generation/Store/UnoStore.php');
		$this->assertFileExists(self::$_base . 'Generation/Store/DosStore.php');
		$this->assertFileExists(self::$_base . 'Generation/Store/TresStore.php');
		$this->assertFileExists(self::$_base . 'Generation/Controller/UnoController.php');
		$this->assertFileExists(self::$_base . 'Generation/Controller/DosController.php');
		$this->assertFileExists(self::$_base . 'Generation/Controller/TresController.php');
	}

	/**
	 * @depends testGenerate
	 */
	public function testGeneratedModels()
	{
		if(!defined('APPLICATION_PATH'))
		{
			define('APPLICATION_PATH', self::$_base);
		}

		require_once(self::$_base . 'Generation/Model/Base/UnoBase.php');
		require_once(self::$_base . 'Generation/Model/Base/DosBase.php');
		require_once(self::$_base . 'Generation/Model/Base/TresBase.php');
		require_once(self::$_base . 'Generation/Model/Uno.php');
		require_once(self::$_base . 'Generation/Model/Dos.php');
		require_once(self::$_base . 'Generation/Model/Tres.php');
		require_once(self::$_base . 'ArrayPropertyModel.php');

		$uno = new Generation\Model\Uno();
		$dos = new Generation\Model\Dos();
		$tres = new Generation\Model\Tres();

		$this->assertTrue($uno instanceof PHPCI\Framework\Model);
		$this->assertTrue($dos instanceof PHPCI\Framework\Model);
		$this->assertTrue($tres instanceof PHPCI\Framework\Model);

		$this->assertTrue($uno instanceof Generation\Model\Base\UnoBase);
		$this->assertTrue($dos instanceof Generation\Model\Base\DosBase);
		$this->assertTrue($tres instanceof Generation\Model\Base\TresBase);

		$this->assertTrue($uno->getTableName() == 'uno');
		$this->assertTrue($dos->getTableName() == 'dos');
		$this->assertTrue($tres->getTableName() == 'tres');

		$uno->setId(1);
		$uno->setFieldDatetime(new DateTime());
		$this->assertTrue($uno->getFieldDatetime() instanceof DateTime);

		$unoArray = $uno->toArray();
		$this->assertArrayHasKey('field_varchar', $unoArray);
		$this->assertTrue($unoArray['field_datetime'] instanceof DateTime);

		Generation\Model\Uno::$sleepable = array('id', 'field_varchar');
		$unoArray = $uno->toArray();
		$this->assertArrayHasKey('field_varchar', $unoArray);
		$this->assertFalse(array_key_exists('field_datetime', $unoArray));

		$tres->setField($uno);
		$this->assertTrue($tres->getFieldInt() == 1);

		$this->assertTrue(in_array('id', $uno->getModified()));
		$this->assertTrue(is_array($uno->getDataArray()));

		$uno->setValues(array('field_int' => 100, 'field_bob' => 100));
		$this->assertFalse(in_array('field_bob', $uno->getModified()));
		$this->assertTrue($uno->getFieldInt() === 100);

		$uno->setFieldInt(true);
		$this->assertTrue($uno->getFieldInt() === 1);

		$caught = false;

		try
		{
			$uno->setFieldInt('invalid');
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertTrue($caught);

		$uno->setFieldInt('500');
		$this->assertTrue($uno->getFieldInt() === 500);

		$caught = false;

		try
		{
			$uno->setFieldFloat('invalid');
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertTrue($caught);

		$uno->setFieldFloat('4.12');
		$this->assertTrue($uno->getFieldFloat() === 4.12);


		$uno->setFieldDatetime('2014-01-01');
		$this->assertTrue($uno->getFieldDatetime() instanceof DateTime);

		$caught = false;

		try
		{
			$uno->setFieldDatetime(2012);
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertTrue($caught);


		$caught = false;

		try
		{
			$uno->setFieldInt(null);
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertTrue($caught);

		$caught = false;

		try
		{
			$uno->setValues(array('field_int' => 'null'));
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertTrue($caught);

		$uno->setValues(array('field_int' => 'true'));
		$this->assertTrue($uno->getFieldInt() === 1);

		$uno->setValues(array('field_int' => 'false'));
		$this->assertTrue($uno->getFieldInt() === 0);

		$caught = false;

		try
		{
			$uno->setFieldVarchar(false);
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertTrue($caught);

		$caught = false;

		try
		{
			$uno->setFieldVarchar('Hi');
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertFalse($caught);

		// Test toArray() with an array property:
		$aModel = new Generation\ArrayPropertyModel();
		$array = $aModel->toArray();

		$this->assertArrayHasKey('array_property', $array);
		$this->assertTrue(is_array($array['array_property']));
		$this->assertTrue(is_array($array['array_property']['three']));
		$this->assertTrue($array['array_property']['one'] == 'two');
	}

	/**
	 * @depends testGeneratedModels
	 */
	public function testGeneratedStores()
	{
		require_once(self::$_base . 'Generation/Store/Base/UnoStoreBase.php');
		require_once(self::$_base . 'Generation/Store/Base/DosStoreBase.php');
		require_once(self::$_base . 'Generation/Store/Base/TresStoreBase.php');
		require_once(self::$_base . 'Generation/Store/UnoStore.php');
		require_once(self::$_base . 'Generation/Store/DosStore.php');
		require_once(self::$_base . 'Generation/Store/TresStore.php');

		$uno = new Generation\Store\UnoStore();
		$dos = new Generation\Store\DosStore();
		$tres = new Generation\Store\TresStore();

		$this->assertTrue($uno instanceof PHPCI\Framework\Store);
		$this->assertTrue($dos instanceof PHPCI\Framework\Store);
		$this->assertTrue($tres instanceof PHPCI\Framework\Store);

		$this->assertTrue($uno instanceof Generation\Store\Base\UnoStoreBase);
		$this->assertTrue($dos instanceof Generation\Store\Base\DosStoreBase);
		$this->assertTrue($tres instanceof Generation\Store\Base\TresStoreBase);

		$tresModel = new Generation\Model\Tres();
		$tresModel->setFieldVarchar('Hi');

		$caught = false;

		try
		{
			$tres->save($tresModel);
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertTrue($caught);


		$caught = false;

		try
		{
			$uno->save($tresModel);
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertTrue($caught);


		$unoModel = new Generation\Model\Uno();
		$unoModel->setFieldVarchar('Hi');

		$unoModel = $uno->save($unoModel);
		$id = $unoModel->getId();
		$this->assertTrue(!empty($id));
		$this->assertTrue($unoModel->getFieldVarchar() == 'Hi');

		$unoModel->setFieldVarchar('Ha');
		$unoModel = $uno->save($unoModel);
		$this->assertTrue($id == $unoModel->getId());
		$this->assertTrue($unoModel->getFieldVarchar() == 'Ha');

		$unoModel = $uno->save($unoModel);
		$this->assertTrue($id == $unoModel->getId());
		$this->assertTrue($unoModel->getFieldVarchar() == 'Ha');

		$unoModel2 = $uno->getByPrimaryKey($id);
		$this->assertTrue($unoModel2->getId() == $unoModel->getId());

		$res = $uno->getWhere(array('field_varchar' => 'Ha'));
		$this->assertTrue($res['count'] != 0);

		$res = $uno->getWhere(array('id' => array('operator' => 'between', 'value' => array(0, 100))));
		$this->assertTrue($res['count'] != 0);

		$res = $uno->getWhere(array('id' => array('operator' => 'IN', 'value' => array(1, 2, 3, 4))));
		$this->assertTrue($res['count'] != 0);

		$res = $uno->getWhere(array('id' => array('operator' => '!=', 'value' => array('null', 100))));
		$this->assertTrue($res['count'] != 0);

		$res = $uno->getWhere(array('id' => array('operator' => '==', 'value' => array('null'))));
		$this->assertTrue($res['count'] == 0);

		$res = $uno->getWhere(array('id' => array('operator' => '==', 'value' => 'null')));
		$this->assertTrue($res['count'] == 0);

		$res = $uno->getWhere(array('id' => array('operator' => '!=', 'value' => 'null')));
		$this->assertTrue($res['count'] != 0);

		$res = $uno->getWhere(array('field_varchar' => array('operator' => 'like', 'value' => 'Ha')));
		$this->assertTrue($res['count'] != 0);

		$res = $uno->getWhere(array('field_varchar' => array('operator' => '!=', 'value' => 'Hi')));
		$this->assertTrue($res['count'] != 0);

		$res = $uno->getWhere(array('field_varchar' => array('Ha', 'Hi')));
		$this->assertTrue($res['count'] != 0);

		$res = $uno->getWhere(array('id' => 1), 1, 0, array('dos' => array('alias' => 'd', 'on' => 'd.id = uno.id')), array('id' => 'ASC'));
		$this->assertTrue($res['count'] != 0);

		$res = $uno->getWhere(array('id' => 1), 1, 0, array('dos' => array('alias' => 'd', 'on' => 'd.id = uno.id')), 'rand');
		$this->assertTrue($res['count'] != 0);

		$res = $uno->getWhere(array('id' => 1), 1, 10);
		$this->assertTrue(count($res['items']) == 0 && $res['count'] == 1);


		$caught = false;

		try
		{
			$uno->getWhere(array('invalid_column' => 1));
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertTrue($caught);

		$res = $uno->getWhere(array('id' => 1), 1, 0, array(), 'rand', array('LEFT JOIN dos d ON d.id = uno.id'));
		$this->assertTrue($res['count'] != 0);


		$res = $uno->getWhere(array('id' => 1), 1, 0, array(), 'rand', array(), 'field_varchar');
		$this->assertTrue($res['count'] != 0);

		$res = $uno->getWhere(array(), 1, 0, array(), 'rand', array(), null, array(array('type' => 'AND', 'query' => 'id = 1', 'params' => array())));
		$this->assertTrue($res['count'] != 0);

		$res = $uno->getWhere(array('id' => 2), 1, 0, array(), 'rand', array(), null, array(array('type' => 'AND', 'query' => 'id = ?', 'params' => array('id'))));
		$this->assertTrue($res['count'] == 0);

		$caught = false;

		try
		{
			$uno->getWhere(array('' => 1));
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertTrue($caught);

		// ----
		// Tests for Model::toArray() with relationships:
		// ----
		$tresModel->setField($unoModel);
		$array = $tresModel->toArray();

		$this->assertTrue(array_key_exists('Field', $array));
		$this->assertTrue(array_key_exists('id', $array['Field']));
		$this->assertTrue($array['Field']['id'] == $unoModel->getId());

		// ----
		// Tests for Store::delete()
		// ----

		$caught = false;
		try
		{
			$tres->delete($tresModel);
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertTrue($caught);

		$caught = false;
		try
		{
			$uno->delete($tresModel);
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertTrue($caught);


		$this->assertTrue($uno->delete($unoModel));
		$this->assertTrue(is_null($uno->getByPrimaryKey(1)));
	}

	/**
	 * @depends testGeneratedStores
	 */
	public function testGeneratedControllers()
	{
		require_once(self::$_base . 'Generation/Controller/Base/UnoControllerBase.php');
		require_once(self::$_base . 'Generation/Controller/Base/DosControllerBase.php');
		require_once(self::$_base . 'Generation/Controller/Base/TresControllerBase.php');
		require_once(self::$_base . 'Generation/Controller/UnoController.php');
		require_once(self::$_base . 'Generation/Controller/DosController.php');
		require_once(self::$_base . 'Generation/Controller/TresController.php');
		require_once(self::$_base . 'TestUser.php');

		$uno = new Generation\Controller\UnoController();
		$dos = new Generation\Controller\DosController();
		$tres = new Generation\Controller\TresController();

		$uno->init();
		$dos->init();
		$tres->init();

		$this->assertTrue($uno instanceof PHPCI\Framework\Controller);
		$this->assertTrue($dos instanceof PHPCI\Framework\Controller);
		$this->assertTrue($tres instanceof PHPCI\Framework\Controller);

		$this->assertTrue($uno instanceof Generation\Controller\Base\UnoControllerBase);
		$this->assertTrue($dos instanceof Generation\Controller\Base\DosControllerBase);
		$this->assertTrue($tres instanceof Generation\Controller\Base\TresControllerBase);


		Registry::getInstance()->setParam('hello', 'world');
		$this->assertTrue($uno->getParam('hello', 'dave') == 'world');

		$uno->setParam('hello', 'dave');
		$this->assertTrue($uno->getParam('hello', 'world') == 'dave');
		$this->assertTrue(array_key_exists('hello', $uno->getParams()));

		$uno->unsetParam('hello');
		$this->assertFalse(array_key_exists('hello', $uno->getParams()));

		$testUser = new \TestUser();
		$uno->setActiveUser($testUser);
		$dos->setActiveUser($uno->getActiveUser());
		$tres->setActiveUser($uno->getActiveUser());

		$unoModel = new Generation\Model\Uno();
		$unoStore = \PHPCI\Framework\Store\Factory::getStore('Uno');
		$unoModel->setFieldVarchar('Hi');

		$unoStore->save($unoModel);
		$list = $uno->index();

		$this->assertTrue(is_array($list));
		$this->assertTrue(is_array($list['items']));
		$this->assertTrue(count($list['items']) > 0);

		$caught = false;
		try
		{
			$dos->index();
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertTrue($caught);


		$first = array_shift($list['items']);

		$uno1 = $uno->get($first['id']);
		$this->assertTrue(is_array($uno1));
		$this->assertTrue(isset($uno1['uno']));
		$this->assertTrue($uno1['uno']['id'] == $first['id']);

		$caught = false;
		try
		{
			$dos->get(1);
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertTrue($caught);


		$uno->setParam('field_varchar', 'Un');
		$uno1 = $uno->put($first['id']);
		$this->assertTrue($uno1['uno']['id'] == $first['id']);

		$caught = false;
		try
		{
			$dos->put(1);
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertTrue($caught);
		$this->assertTrue(is_null($uno->put(10000)));


		$uno->setParam('field_text', 'Hello');
		$res = $uno->post();
		$this->assertTrue($res['uno']['field_varchar'] == 'Un');
		$this->assertTrue(!empty($res['uno']['id']));

		$caught = false;
		try
		{
			$dos->post();
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertTrue($caught);

		$del = $uno->delete($res['uno']['id']);
		$this->assertTrue($del['deleted']);

		$del = $uno->delete($res['uno']['id']);
		$this->assertFalse($del['deleted']);

		$del = $tres->delete(100);
		$this->assertFalse($del['deleted']);

		$caught = false;
		try
		{
			$dos->delete(1000);
		}
		catch(Exception $ex)
		{
			$caught = true;
		}

		$this->assertTrue($caught);

		//----
		// Tests for _parseWhere()
		//----
		$uno->setParam('where', array('id' => array(1000)));
		$uno->setParam('neq', 'id');
		$list = $uno->index();

		$this->assertTrue(is_array($list));
		$this->assertTrue(count($list['items']) != 0);

		Registry::getInstance()->forceReset();
		$uno->setParam('where', array('id' => 1000));
		$uno->setParam('fuzzy', 'id');
		$list = $uno->index();

		$this->assertTrue(is_array($list));
		$this->assertTrue(count($list['items']) == 0);
	}
}
