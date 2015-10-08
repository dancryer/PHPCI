<?php

use Phinx\Migration\AbstractMigration;

class AddProjectGroups extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('project_group');
        $table->addColumn('title', 'string', array('limit' => 100, 'null' => false));
        $table->save();

        $group = new \PHPCI\Model\ProjectGroup();
        $group->setTitle('Projects');

        /** @var \PHPCI\Model\ProjectGroup $group */
        $group = \b8\Store\Factory::getStore('ProjectGroup')->save($group);

        $table = $this->table('project');
        $table->addColumn('group_id', 'integer', array(
            'signed' => true,
            'null' => false,
            'default' => $group->getId(),
        ));

        $table->addForeignKey('group_id', 'project_group', 'id', array('delete'=> 'RESTRICT', 'update' => 'CASCADE'));
        $table->save();
    }
}
