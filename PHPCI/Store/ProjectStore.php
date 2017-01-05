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
use PHPCI\Model\Project;
use PHPCI\Model\ProjectCollection;
use PHPCI\Store\Base\ProjectStoreBase;

/**
* Project Store
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Core
*/
class ProjectStore extends ProjectStoreBase
{
    /**
     * Returns a list of all branch names PHPCI has run builds against.
     * @param $projectId
     * @return array
     */
    public function getKnownBranches($projectId)
    {
        $query = 'SELECT DISTINCT branch from build WHERE project_id = :pid';
        $stmt = Connection::get()->prepare($query);
        $stmt->bindValue(':pid', $projectId);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return $item['branch'];
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }

    /**
     * Get a list of all projects, ordered by their title.
     * @return ProjectCollection
     */
    public function getAll() : ProjectCollection
    {
        return $this->find()->order('title', 'ASC')->get(1000);
    }

    /**
     * Get multiple Project by GroupId.
     * @param int $value
     * @param int $limit
     * @param string $useConnection
     * @return ProjectCollection
     * @throws \Exception
     */
    public function getByGroupId($value, $limit = 1000, $useConnection = 'read') : ProjectCollection
    {
        if (is_null($value)) {
            throw new \Exception('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        return $this->where('group_id', $value)->order('title', 'ASC')->get($limit);
    }
}
