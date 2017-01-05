<?php

/**
 * BuildMeta model collection
 */

namespace PHPCI\Model;

use Block8\Database\Model\Collection;

/**
 * BuildMeta Model Collection
 */
class BuildMetaCollection extends Collection
{
    /**
     * Add a BuildMeta model to the collection.
     * @param string $key
     * @param BuildMeta $value
     * @return BuildMetaCollection
     */
    public function addBuildMeta($key, BuildMeta $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return BuildMeta|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
