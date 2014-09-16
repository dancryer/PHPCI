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
use PHPCI\Store\Base\ProjectStoreBase;

/**
* Project Store
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Core
*/
class ProjectStore extends ProjectStoreBase
{
    public function getKnownBranches($projectId)
    {
        $query = 'SELECT DISTINCT branch from build WHERE project_id = :pid';
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':pid', $projectId);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return $item['branch'];
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return array();
        }
    }
}
