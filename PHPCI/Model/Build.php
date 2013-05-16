<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Model;

use PHPCI\Model\Base\BuildBase;
use PHPCI\Builder;

/**
* Build Model
* @uses         PHPCI\Model\Base\BuildBase
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Core
*/
class Build extends BuildBase
{
    public function getCommitLink()
    {
        return '#';
    }

    public function getBranchLink()
    {
        return '#';
    }

    public function sendStatusPostback()
    {
        return;
    }

    public function createWorkingCopy(Builder $builder, $buildPath)
    {
    }
}
