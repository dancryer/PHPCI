<?php

/**
 * ProjectGroup base model for table: project_group
 */

namespace PHPCI\Model\Base;

use DateTime;
use Block8\Database\Query;
use PHPCI\Model;
use PHPCI\Model\ProjectGroup;
use PHPCI\Store;
use PHPCI\Store\ProjectGroupStore;

/**
 * ProjectGroup Base Model
 */
abstract class ProjectGroupBase extends Model
{
    protected $table = 'project_group';
    protected $model = 'ProjectGroup';
    protected $data = [
        'id' => null,
        'title' => null,
    ];

    protected $getters = [
        'id' => 'getId',
        'title' => 'getTitle',
    ];

    protected $setters = [
        'id' => 'setId',
        'title' => 'setTitle',
    ];

    /**
     * Return the database store for this model.
     * @return ProjectGroupStore
     */
    public static function Store() : ProjectGroupStore
    {
        return ProjectGroupStore::load();
    }

    
    /**
     * Get the value of Id / id
     * @return int
     */

     public function getId() : int
     {
        $rtn = $this->data['id'];

        return $rtn;
     }
    
    /**
     * Get the value of Title / title
     * @return string
     */

     public function getTitle() : string
     {
        $rtn = $this->data['title'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     * @return ProjectGroup
     */
    public function setId(int $value) : ProjectGroup
    {

        if ($this->data['id'] !== $value) {
            $this->data['id'] = $value;
            $this->setModified('id');
        }

        return $this;
    }
    
    /**
     * Set the value of Title / title
     * @param $value string
     * @return ProjectGroup
     */
    public function setTitle(string $value) : ProjectGroup
    {

        if ($this->data['title'] !== $value) {
            $this->data['title'] = $value;
            $this->setModified('title');
        }

        return $this;
    }
    
    

    public function Projects() : Query
    {
        return Store::get('Project')->where('group_id', $this->data['id']);
    }
}
