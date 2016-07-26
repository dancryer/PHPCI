<?php

use Phinx\Migration\AbstractMigration;

class AddProjectGroups extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('project_group');
        $table->addColumn('title', 'string', ['limit' => 100, 'null' => false]);
        $table->save();

        $group = new \PHPCI\Model\ProjectGroup();
        $group->setTitle('Projects');

        /** @type \PHPCI\Model\ProjectGroup $group */
        $group = \b8\Store\Factory::getStore('ProjectGroup')->save($group);

        $table = $this->table('project');
        $table->addColumn('group_id', 'integer', [
            'signed'  => true,
            'null'    => false,
            'default' => $group->getId(),
        ]);

        $table->addForeignKey('group_id', 'project_group', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE']);
        $table->save();
    }
}
