<?php

use Phinx\Migration\AbstractMigration;

class DatabaseFixes extends AbstractMigration
{
    public function change()
    {
        $project = $this->table('project');
        $project->changeColumn('archived', 'boolean', ['null' => false, 'default' => 0]);
        $project->save();

        $errors = $this->table('build_error');
        $errors->changeColumn('message', 'text');
        $errors->save();
    }
}
