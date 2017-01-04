<?php

namespace PHPCI\Framework\Form;
use PHPCI\Framework\Form\Element,
    PHPCI\Framework\View;

class Input extends Element
{
	protected $_required = false;
	protected $_pattern;
	protected $_validator;
	protected $_value;
	protected $_error;
    protected $_customError = false;

    public static function create($name, $label, $required = false)
    {
        $el = new static();
        $el->setName($name);
        $el->setLabel($label);
        $el->setRequired($required);

        return $el;
    }

	public function getValue()
	{
		return $this->_value;
	}

	public function setValue($value)
	{
		$this->_value = $value;
        return $this;
	}

	public function getRequired()
	{
		return $this->_required;
	}

	public function setRequired($required)
	{
		$this->_required = (bool)$required;
        return $this;
	}

	public function getValidator()
	{
		return $this->_validator;
	}

	public function setValidator($validator)
	{
		if(is_callable($validator) || $validator instanceof \Closure)
		{
			$this->_validator = $validator;
		}

        return $this;
	}

	public function getPattern()
	{
		return $this->_pattern;
	}

	public function setPattern($pattern)
	{
		$this->_pattern = $pattern;
        return $this;
	}

	public function validate()
	{
		if($this->getRequired() && empty($this->_value))
		{
			$this->_error = $this->getLabel() . ' is required.';
			return false;
		}

		if($this->getPattern() && !preg_match('/'.$this->getPattern().'/', $this->_value))
		{
			$this->_error = 'Invalid value entered.';
			return false;
		}

		$validator = $this->getValidator();

		if(is_callable($validator))
		{
			try
			{
				call_user_func_array($validator, array($this->_value));
			}
			catch(\Exception $ex)
			{
				$this->_error = $ex->getMessage();
				return false;
			}
		}

        if ($this->_customError) {
            return false;
        }

		return true;
	}

    public function setError($message)
    {
        $this->_customError = true;
        $this->_error = $message;
        return $this;
    }

	protected function _onPreRender(View &$view)
	{
		$view->value    = $this->getValue();
		$view->error    = $this->_error;
		$view->pattern  = $this->_pattern;
		$view->required = $this->_required;
	}
}