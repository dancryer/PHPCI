<?php

/**
 * Build model collection
 */

namespace PHPCI\Model;

use Block8\Database\Model\Collection;

/**
 * Build Model Collection
 */
class BuildCollection extends Collection
{
    /**
     * Add a Build model to the collection.
     * @param string $key
     * @param Build $value
     * @return BuildCollection
     */
    public function addBuild($key, Build $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return Build|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
