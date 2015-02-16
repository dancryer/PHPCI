<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Model\Build;

use PHPCI\Model\Build\GitlabBuild;

/**
 * Gitlab Build Model
 * @author       Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 * @package      PHPCI
 * @subpackage   Core
 */
class BeanstalkBuild extends GitlabBuild
{
    /**
     * Get link to commit from another source (i.e. Github)
     */
    public function getCommitLink()
    {
        return $this->getExtra('changeset_url');
    }
}
