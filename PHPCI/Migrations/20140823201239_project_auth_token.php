<?php

use Phinx\Migration\AbstractMigration;

class ProjectAuthToken extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $project = $this->table('project');
        $project->addColumn('auth_token', 'string', array(
            'after' => 'branch',
            'limit' => 250
        ))->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $project = $this->table('project');
        $project->removeColumn('auth_token')->save();
    }
}
