<?php

use Phinx\Migration\AbstractMigration;

class ChangeBuildKeysMigration extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $project = $this->table('project');
        $project->renameColumn('git_key', 'ssh_private_key');
        $project->renameColumn('public_key', 'ssh_public_key');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $project = $this->table('project');
        $project->renameColumn('ssh_private_key', 'git_key');
        $project->renameColumn('ssh_public_key', 'public_key');
    }
}
