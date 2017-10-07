<?php

use Phinx\Migration\AbstractMigration;

class AddBuildConfigFileColumn extends AbstractMigration
{
    public function change()
    {
        $project = $this->table('project');
        $project->addColumn('build_config_file', 'string', array('limit' => 250));
        $project->save();
    }
}
