<?php

/**
 * Project model collection
 */

namespace PHPCI\Model;

use Block8\Database\Model\Collection;

/**
 * Project Model Collection
 */
class ProjectCollection extends Collection
{
    /**
     * Add a Project model to the collection.
     * @param string $key
     * @param Project $value
     * @return ProjectCollection
     */
    public function addProject($key, Project $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return Project|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
