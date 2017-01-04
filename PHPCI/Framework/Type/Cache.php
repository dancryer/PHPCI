<?php

namespace PHPCI\Framework\Type;

interface Cache
{
	public function get($key, $default = null);
	public function set($key, $value = null, $ttl = 0);
	public function delete($key);
	public function contains($key);
	public function isEnabled();

	public function __get($key);
	public function __set($key, $value = null);
	public function __unset($key);
	public function __isset($key);
}