<?php

/**
 * BuildError store for table: build_error
 */

namespace PHPCI\Store;

use Block8\Database\Query;
use PHPCI\Model\BuildCollection;
use PHPCI\Model\BuildError;
use PHPCI\Model\BuildErrorCollection;
use PHPCI\Store\Base\BuildErrorStoreBase;

/**
 * BuildError Store
 * @uses PHPCI\Store\Base\BuildErrorStoreBase
 */
class BuildErrorStore extends BuildErrorStoreBase
{
    /**
     * Get a list of errors for a given build, since a given time.
     * @param int $buildId
     * @param string|null $since date string
     * @return BuildErrorCollection
     */
    public function getErrorsForBuild(int $buildId, $since = null) : BuildErrorCollection
    {
        $query = $this->where('build_id', $buildId);

        if (!is_null($since)) {
            $query->and('created_date', $since, Query::GREATER_THAN);
        }

        return $query->get(15000);
    }

    /**
     * Gets the total number of errors for a given build.
     * @param int $buildId
     * @return int
     */
    public function getErrorTotalForBuild(int $buildId) : int
    {
        return $this->where('build_id', $buildId)->count();
    }
}
