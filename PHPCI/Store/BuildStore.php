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
    /**
     * Return an array of the latest builds for a given project.
     * @param null $projectId
     * @param int $limit
     * @return array
     */
    public function getLatestBuilds($projectId = null, $limit = 5)
    {
        if (!is_null($projectId)) {
            $query = 'SELECT * FROM build WHERE `project_id` = :pid ORDER BY id DESC LIMIT :limit';
        } else {
            $query = 'SELECT * FROM build ORDER BY id DESC LIMIT :limit';
        }

        $stmt = Database::getConnection('read')->prepare($query);

        if (!is_null($projectId)) {
            $stmt->bindValue(':pid', $projectId);
        }

        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

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

    /**
     * Return the latest build for a specific project, of a specific build status.
     * @param null $projectId
     * @param int $status
     * @return array|Build
     */
    public function getLastBuildByStatus($projectId = null, $status = Build::STATUS_SUCCESS)
    {
        $query = 'SELECT * FROM build WHERE project_id = :pid AND status = :status ORDER BY id DESC LIMIT 1';
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':pid', $projectId);
        $stmt->bindValue(':status', $status);

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return new Build($data);
            }
        } else {
            return array();
        }
    }

    /**
     * Return an array of builds for a given project and commit ID.
     * @param $projectId
     * @param $commitId
     * @return array
     */
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

    /**
     * Return build metadata by key, project and optionally build id.
     * @param $key
     * @param $projectId
     * @param null $buildId
     * @param null $branch
     * @param int $numResults
     * @return array|null
     */
    public function getMeta($key, $projectId, $buildId = null, $branch = null, $numResults = 1)
    {
        $query = 'SELECT bm.build_id, bm.meta_key, bm.meta_value
                    FROM build_meta AS bm
                    LEFT JOIN build b ON b.id = bm.build_id
                    WHERE   bm.meta_key = :key
                      AND   bm.project_id = :projectId';

        // If we're getting comparative meta data, include previous builds
        // otherwise just include the specified build ID:
        if ($numResults > 1) {
            $query .= ' AND bm.build_id <= :buildId ';
        } else {
            $query .= ' AND bm.build_id = :buildId ';
        }

        // Include specific branch information if required:
        if (!is_null($branch)) {
            $query .= ' AND b.branch = :branch ';
        }

        $query .= ' ORDER BY bm.id DESC LIMIT :numResults';

        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':key', $key, \PDO::PARAM_STR);
        $stmt->bindValue(':projectId', (int)$projectId, \PDO::PARAM_INT);
        $stmt->bindValue(':buildId', (int)$buildId, \PDO::PARAM_INT);
        $stmt->bindValue(':numResults', (int)$numResults, \PDO::PARAM_INT);
        $stmt->bindValue(':branch', $branch, \PDO::PARAM_STR);

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

    /**
     * Set a metadata value for a given project and build ID.
     * @param $projectId
     * @param $buildId
     * @param $key
     * @param $value
     * @return bool
     */
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
