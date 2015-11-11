<?php

/**
 * BuildError store for table: build_error
 */

namespace PHPCI\Store;

use b8\Database;
use PHPCI\Model\BuildError;
use PHPCI\Store\Base\BuildErrorStoreBase;

/**
 * BuildError Store
 * @uses PHPCI\Store\Base\BuildErrorStoreBase
 */
class BuildErrorStore extends BuildErrorStoreBase
{
    /**
     * Get a list of errors for a given build, since a given time.
     * @param $buildId
     * @param string $since date string
     * @return array
     */
    public function getErrorsForBuild($buildId, $since = null)
    {
        $query = 'SELECT * FROM build_error
                    WHERE build_id = :build';

        if (!is_null($since)) {
            $query .= ' AND created_date > :since';
        }

        $query .= ' LIMIT 15000';

        $stmt = Database::getConnection('read')->prepare($query);

        $stmt->bindValue(':build', $buildId, \PDO::PARAM_INT);

        if (!is_null($since)) {
            $stmt->bindValue(':since', $since);
        }

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new BuildError($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return array();
        }
    }
}
