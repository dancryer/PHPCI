<?php

namespace PHPCI;

interface Plugin
{
	public function __construct(\PHPCI\Builder $phpci, array $options = array());
	public function execute();
}