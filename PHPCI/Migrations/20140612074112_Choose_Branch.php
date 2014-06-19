<?php

use Phinx\Migration\AbstractMigration;

class ChooseBranch extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $project = $this->table('project');
        
        if (!$project->hasColumn('default_branch')) {
        $project->addColumn('default_branch', 'string', array(
            'after' => 'reference',
            'limit' => 250
        ));
        }
        $project->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $project = $this->table('project');
        if ($project->hasColumn('default_branch')) {
        	$project->removeColumn('default_branch')->save();
        }
    }
}