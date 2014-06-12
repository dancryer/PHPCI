<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Store;

use b8\Database;
use PHPCI\Model\Build;
use PHPCI\Store\Base\BuildStoreBase;

/**
* Build Store
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Core
*/
class BuildStore extends BuildStoreBase
{
    public function getLatestBuilds($projectId)
    {
        $query = 'SELECT * FROM build WHERE project_id = :pid ORDER BY id DESC LIMIT 5';
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':pid', $projectId);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new \PHPCI\Model\Build($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return array();
        }
    }

    public function getByProjectAndCommit($projectId, $commitId)
    {
        $query = 'SELECT * FROM `build` WHERE `project_id` = :project_id AND `commit_id` = :commit_id';
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':project_id', $projectId);
        $stmt->bindValue(':commit_id', $commitId);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Build($item);
            };

            $rtn = array_map($map, $res);

            return array('items' => $rtn, 'count' => count($rtn));
        } else {
            return array('items' => array(), 'count' => 0);
        }
    }

    public function getMeta($key, $projectId, $buildId = null, $numResults = 1)
    {
        $select = '`build_id`, `meta_key`, `meta_value`';
        $and = $numResults > 1 ? ' AND (`build_id` <= :buildId) ' : ' AND (`build_id` = :buildId) ';
        $where = '`meta_key` = :key AND `project_id` = :projectId ' . $and;
        $query = 'SELECT '.$select.' FROM `build_meta` WHERE '.$where.' ORDER BY id DESC LIMIT :numResults';

        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':key', $key, \PDO::PARAM_STR);
        $stmt->bindValue(':projectId', (int)$projectId, \PDO::PARAM_INT);
        $stmt->bindValue(':buildId', (int)$buildId, \PDO::PARAM_INT);
        $stmt->bindValue(':numResults', (int)$numResults, \PDO::PARAM_INT);

        if ($stmt->execute()) {
            $rtn = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $rtn = array_reverse($rtn);
            $rtn = array_map(function ($item) {
                $item['meta_value'] = json_decode($item['meta_value'], true);
                return $item;
            }, $rtn);

            if (!count($rtn)) {
                return null;
            } else {
                return $rtn;
            }

        } else {
            return null;
        }
    }

    public function setMeta($projectId, $buildId, $key, $value)
    {
        $cols = '`project_id`, `build_id`, `meta_key`, `meta_value`';
        $query = 'REPLACE INTO build_meta ('.$cols.') VALUES (:projectId, :buildId, :key, :value)';

        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':key', $key, \PDO::PARAM_STR);
        $stmt->bindValue(':projectId', (int)$projectId, \PDO::PARAM_INT);
        $stmt->bindValue(':buildId', (int)$buildId, \PDO::PARAM_INT);
        $stmt->bindValue(':value', $value, \PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
