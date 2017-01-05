<?php

/**
 * BuildError model collection
 */

namespace PHPCI\Model;

use Block8\Database\Model\Collection;

/**
 * BuildError Model Collection
 */
class BuildErrorCollection extends Collection
{
    /**
     * Add a BuildError model to the collection.
     * @param string $key
     * @param BuildError $value
     * @return BuildErrorCollection
     */
    public function addBuildError($key, BuildError $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return BuildError|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
