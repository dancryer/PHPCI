<?php

namespace Tests\PHPCI\Service;

use b8\Model;
use PHPCI\Store\ProjectStore;

class MockProjectStore extends ProjectStore
{
    public function save(Model $project)
    {
        $project->setId(1);
        return $project;
    }
}
