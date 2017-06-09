<?php

use Phinx\Migration\AbstractMigration;

class AddObserveBranchesColumn extends AbstractMigration
{
    public function change()
    {
        $project = $this->table('project');
        $project->addColumn('observed_branches', 'text');
        $project->save();
    }
}
