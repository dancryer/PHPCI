<?php

namespace PHPCI\Framework;

use PHPCI\Framework\Exception\HttpException;
use PHPCI\Framework\Cache;

class Model
{
	public static $sleepable    = array();
	protected $getters         = array();
	protected $setters         = array();
	protected $data            = array();
	protected $modified        = array();
	protected $tableName;
	protected $cache;

	public function __construct($initialData = array())
	{
		if(is_array($initialData))
		{
			$this->data = array_merge($this->data, $initialData);
		}

		$this->cache = Cache::getCache(Cache::TYPE_REQUEST);
	}

	public function getTableName()
	{
		return $this->tableName;
	}

	public function toArray($depth = 2, $currentDepth = 0)
	{
		if(isset(static::$sleepable) && is_array(static::$sleepable) && count(static::$sleepable))
		{
			$sleepable = static::$sleepable;
		}
		else
		{
			$sleepable = array_keys($this->getters);
		}

		$rtn = array();
		foreach($sleepable as $property)
		{
			$rtn[$property] = $this->_propertyToArray($property, $currentDepth, $depth);
		}

		return $rtn;
	}

	protected function _propertyToArray($property, $currentDepth, $depth)
	{
		$rtn = null;

		if(array_key_exists($property, $this->getters))
		{
			$method = $this->getters[$property];
			$rtn    = $this->{$method}();

			if(is_object($rtn) || is_array($rtn))
			{
				$rtn = ($depth > $currentDepth) ? $this->_valueToArray($rtn, $currentDepth, $depth) : null;
			}
		}

		return $rtn;
	}

	protected function _valueToArray($value, $currentDepth, $depth)
	{
		$rtn = null;
		if(!is_null($value))
		{
			if(is_object($value) && method_exists($value, 'toArray'))
			{
				$rtn = $value->toArray($depth, $currentDepth + 1);
			}
			elseif(is_array($value))
			{
				$childArray = array();

				foreach($value as $k => $v)
				{
					$childArray[$k] = $this->_valueToArray($v, $currentDepth + 1, $depth);
				}

				$rtn = $childArray;
			}
			else
			{
				$rtn = (is_string($value) && !mb_check_encoding($value, 'UTF-8')) ? mb_convert_encoding($value, 'UTF-8') : $value;
			}
		}

		return $rtn;
	}

	public function getDataArray()
	{
		return $this->data;
	}

	public function getModified()
	{
		return $this->modified;
	}

	public function setValues(array $values)
	{
		foreach($values as $key => $value)
		{
			if(isset($this->setters[$key]))
			{
				$func = $this->setters[$key];

				if($value === 'null')
				{
					$value = null;
				}
				elseif($value === 'true')
				{
					$value = true;
				}
				elseif($value === 'false')
				{
					$value = false;
				}

				$this->{$func}($value);
			}
		}
	}

	protected function _setModified($column)
	{
		$this->modified[$column] = $column;
	}

	//----------------
	// Validation
	//----------------
	protected function _validateString($name, $value)
	{
		if(!is_string($value) && !is_null($value))
		{
			throw new HttpException\ValidationException($name . ' must be a string.');
		}
	}

	protected function _validateInt($name, &$value)
	{
		if(is_bool($value))
		{
			$value = $value ? 1 : 0;
		}

		if(!is_numeric($value) && !is_null($value))
		{
			throw new HttpException\ValidationException($name . ' must be an integer.');
		}

		if(!is_int($value) && !is_null($value))
		{
			$value = (int)$value;
		}
	}

	protected function _validateFloat($name, &$value)
	{
		if(!is_numeric($value) && !is_null($value))
		{
			throw new HttpException\ValidationException($name . ' must be a float.');
		}

		if(!is_float($value) && !is_null($value))
		{
			$value = (float)$value;
		}
	}

	protected function _validateDate($name, &$value)
	{
		if(is_string($value))
		{
			$value = empty($value) ? null : new \DateTime($value);
		}

		if((!is_object($value) || !($value instanceof \DateTime)) && !is_null($value))
		{
			throw new HttpException\ValidationException($name . ' must be a date object.');
		}


		$value = empty($value) ? null : $value->format('Y-m-d H:i:s');
	}

	protected function _validateNotNull($name, &$value)
	{
		if(is_null($value))
		{
			throw new HttpException\ValidationException($name . ' must not be null.');
		}
	}

    public function __get($key)
    {
        if (array_key_exists($key, $this->getters)) {
            $getter = $this->getters[$key];
            return $this->{$getter}();
        }

        return null;
    }

    public function __set($key, $value)
    {
        if (array_key_exists($key, $this->setters)) {
            $setter = $this->setters[$key];
            return $this->{$setter}($value);
        }
    }
}
