<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Helper;

class UnixCommandExecutor extends BaseCommandExecutor
{
    protected function findGlobalBinary($binary)
    {
        return trim(shell_exec('which ' . $binary));
    }
}
