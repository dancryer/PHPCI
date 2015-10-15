<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Store;

use PHPCI\Store\Base\BuildMetaStoreBase;
use b8\Database;
use PHPCI\Model\BuildMeta;

/**
 * BuildMeta Store
 * @uses PHPCI\Store\Base\BuildMetaStoreBase
 */
class BuildMetaStore extends BuildMetaStoreBase
{
    public function getErrorsForUpgrade($start, $limit)
    {
        $query = 'SELECT * FROM build_meta
                    WHERE meta_key IN (\'phpmd-data\', \'phpcs-data\', \'phpdoccheck-data\')
                    ORDER BY id ASC LIMIT :start, :limit';

        $stmt = Database::getConnection('read')->prepare($query);

        $stmt->bindValue(':start', $start, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new BuildMeta($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return array();
        }
    }
}
