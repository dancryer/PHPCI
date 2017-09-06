<?php

/**
 * ProjectGroup model collection
 */

namespace PHPCI\Model;

use Block8\Database\Model\Collection;

/**
 * ProjectGroup Model Collection
 */
class ProjectGroupCollection extends Collection
{
    /**
     * Add a ProjectGroup model to the collection.
     * @param string $key
     * @param ProjectGroup $value
     * @return ProjectGroupCollection
     */
    public function addProjectGroup($key, ProjectGroup $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return ProjectGroup|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
