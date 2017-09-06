<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Store;

use Block8\Database\Connection;
use PHPCI\Model\Build;
use PHPCI\Model\BuildCollection;
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
     * @return BuildCollection
     */
    public function getLatestBuilds($projectId = null, $limit = 5) : BuildCollection
    {
        $query = $this->find();

        if (!is_null($projectId)) {
            $query->where('project_id', $projectId);
        }

        return $query->order('id', 'DESC')->get($limit);
    }

    /**
     * Return the latest build for a specific project, of a specific build status.
     * @param null $projectId
     * @param int $status
     * @return Build|null
     */
    public function getLastBuildByStatus($projectId = null, $status = Build::STATUS_SUCCESS)
    {
        return $this->where('project_id', $projectId)
            ->and('status', $status)
            ->order('id', 'DESC')
            ->first();
    }

    /**
     * Return an array of builds for a given project and commit ID.
     * @param $projectId
     * @param $commitId
     * @return BuildCollection
     */
    public function getByProjectAndCommit($projectId, $commitId) : BuildCollection
    {
        return $this->where('project_id', $projectId)
            ->and('commit_id', $commitId)
            ->get();
    }

    /**
     * Returns all registered branches for project
     *
     * @param $projectId
     * @return array
     * @throws \Exception
     */
    public function getBuildBranches($projectId)
    {
        $query = 'SELECT DISTINCT `branch` FROM `build` WHERE `project_id` = :project_id';
        $stmt = Connection::get()->prepare($query);
        $stmt->bindValue(':project_id', $projectId);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_COLUMN);
            return $res;
        } else {
            return [];
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

        $stmt = Connection::get()->prepare($query);
        $stmt->bindValue(':key', $key, \PDO::PARAM_STR);
        $stmt->bindValue(':projectId', (int)$projectId, \PDO::PARAM_INT);
        $stmt->bindValue(':buildId', (int)$buildId, \PDO::PARAM_INT);
        $stmt->bindValue(':numResults', (int)$numResults, \PDO::PARAM_INT);

        if (!is_null($branch)) {
            $stmt->bindValue(':branch', $branch, \PDO::PARAM_STR);
        }

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

        $stmt = Connection::get()->prepare($query);
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
