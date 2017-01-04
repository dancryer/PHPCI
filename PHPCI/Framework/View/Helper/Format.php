<?php

namespace PHPCI\Framework\View\Helper;

class Format
{
	public function Currency($number, $symbol = true)
	{
		return ($symbol ? '£' : '') . number_format($number, 2, '.', ',');
	}
}