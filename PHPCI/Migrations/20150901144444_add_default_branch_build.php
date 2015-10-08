<?php

use Phinx\Migration\AbstractMigration;

class AddDefaultBranchBuild extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $dbAdapter = $this->getAdapter();

        if ($dbAdapter instanceof \Phinx\Db\Adapter\PdoAdapter) {
            $pdo = $dbAdapter->getConnection();
            $pdo->exec('SET foreign_key_checks = 0');
        }

        $project = $this->table('project');

        if (!$project->hasColumn('default_branch_only')) {
            $table->addColumn('default_branch_only', 'integer');
        }

        if ($dbAdapter instanceof \Phinx\Db\Adapter\PdoAdapter) {
            $pdo = $dbAdapter->getConnection();
            $pdo->exec('SET foreign_key_checks = 1');
        }
    }
}
