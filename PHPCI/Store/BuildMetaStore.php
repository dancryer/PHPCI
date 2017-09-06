<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Store;

use PHPCI\Model\BuildMetaCollection;
use PHPCI\Store\Base\BuildMetaStoreBase;

/**
 * BuildMeta Store
 * @uses PHPCI\Store\Base\BuildMetaStoreBase
 */
class BuildMetaStore extends BuildMetaStoreBase
{
    /**
     * Only used by an upgrade migration to move errors from build_meta to build_error
     * @param $start
     * @param $limit
     * @return BuildMetaCollection
     */
    public function getErrorsForUpgrade($limit) : BuildMetaCollection
    {
        return $this->find()
            ->rawWhere('meta_key IN (\'phpmd-data\', \'phpcs-data\', \'phpdoccheck-data\')')
            ->order('id', 'ASC')
            ->get($limit);
    }
}
