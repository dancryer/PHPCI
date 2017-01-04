<?php

namespace PHPCI\Framework\Store;
use PHPCI\Config;

class Factory
{
	/**
	 * @var \PHPCI\Framework\Store\Factory
	 */
	protected static $instance;

	/**
	 * A collection of the stores currently loaded by the factory.
	 * @var \PHPCI\Framework\Store[]
	 */
	protected $loadedStores = array();

	/**
	 * @return Factory
	 */
	public static function getInstance()
	{
		if(!isset(self::$instance))
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param $storeName string Store name (should match a model name).
	 *
	 * @return \PHPCI\Framework\Store
	 */
	public static function getStore($storeName, $namespace = null)
	{
		$factory = self::getInstance();
		return $factory->loadStore($storeName, $namespace);
	}

	protected function __construct()
	{
	}

	/**
	 * @param $store
	 *
	 * @return \PHPCI\Framework\Store;
	 */
	public function loadStore($store, $namespace = null)
	{
		if(!isset($this->loadedStores[$store]))
		{
            $namespace = is_null($namespace) ? 'PHPCI' : $namespace;
			$class =  $namespace . '\\Store\\' . $store . 'Store';
			$obj   = new $class();

			$this->loadedStores[$store] = $obj;
		}

		return $this->loadedStores[$store];
	}
}
