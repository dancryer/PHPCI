<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Store;

use PHPCI\Store\Base\BuildStoreBase;

/**
* Build Store
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Core
*/
class BuildStore extends BuildStoreBase
{
    public function getBuildSummary()
    {
        $query = 'SELECT COUNT(*) AS cnt FROM build b LEFT JOIN project p on p.id = b.project_id GROUP BY b.project_id ORDER BY p.title ASC, b.id DESC';
        $stmt = \b8\Database::getConnection('read')->prepare($query);

        if ($stmt->execute()) {
            $res    = $stmt->fetch(\PDO::FETCH_ASSOC);
            $count  = (int)$res['cnt'];
        } else {
            $count = 0;
        }

        $query = 'SELECT b.* FROM build b LEFT JOIN project p on p.id = b.project_id ORDER BY p.title ASC, b.id DESC';
        $stmt = \b8\Database::getConnection('read')->prepare($query);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new \PHPCI\Model\Build($item);
            };
            $rtn = array_map($map, $res);

            return array('items' => $rtn, 'count' => $count);
        } else {
            return array('items' => array(), 'count' => 0);
        }
    }
}
