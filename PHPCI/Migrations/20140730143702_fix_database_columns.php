<?php

use Phinx\Migration\AbstractMigration;

class FixDatabaseColumns extends AbstractMigration
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

        $build = $this->table('build');
        $build->changeColumn('project_id', 'integer', ['null' => false]);
        $build->changeColumn('commit_id', 'string', ['limit' => 50, 'null' => false]);
        $build->changeColumn('status', 'integer', ['null' => false]);
        $build->changeColumn('log', 'text', ['null' => true]);
        $build->changeColumn('branch', 'string', ['limit' => 50, 'null' => false, 'default' => 'master']);
        $build->changeColumn('created', 'datetime', ['null' => true]);
        $build->changeColumn('started', 'datetime', ['null' => true]);
        $build->changeColumn('finished', 'datetime', ['null' => true]);
        $build->changeColumn('committer_email', 'string', ['limit' => 512, 'null' => true]);
        $build->changeColumn('commit_message', 'text', ['null' => true]);
        $build->changeColumn('extra', 'text', ['null' => true]);

        $buildMeta = $this->table('build_meta');
        $buildMeta->changeColumn('project_id', 'integer', ['null' => false]);
        $buildMeta->changeColumn('build_id', 'integer', ['null' => false]);
        $buildMeta->changeColumn('meta_key', 'string', ['limit' => 250, 'null' => false]);
        $buildMeta->changeColumn('meta_value', 'text', ['null' => false]);

        $project = $this->table('project');
        $project->changeColumn('title', 'string', ['limit' => 250, 'null' => false]);
        $project->changeColumn('reference', 'string', ['limit' => 250, 'null' => false]);
        $project->changeColumn('branch', 'string', ['limit' => 50, 'null' => false, 'default' => 'master']);
        $project->changeColumn('ssh_private_key', 'text', ['null' => true, 'default' => null]);
        $project->changeColumn('ssh_public_key', 'text', ['null' => true, 'default' => null]);
        $project->changeColumn('type', 'string', ['limit' => 50, 'null' => false]);
        $project->changeColumn('access_information', 'string', ['limit' => 250, 'null' => true, 'default' => null]);
        $project->changeColumn('last_commit', 'string', ['limit' => 250, 'null' => true, 'default' => null]);
        $project->changeColumn('ssh_public_key', 'text', ['null' => true, 'default' => null]);
        $project->changeColumn('allow_public_status', 'integer', ['null' => false, 'default' => 0]);

        $user = $this->table('user');
        $user->changeColumn('email', 'string', ['limit' => 250, 'null' => false]);
        $user->changeColumn('hash', 'string', ['limit' => 250, 'null' => false]);
        $user->changeColumn('is_admin', 'integer', ['null' => false, 'default' => 0]);
        $user->changeColumn('name', 'string', ['limit' => 250, 'null' => false]);

        if ($dbAdapter instanceof \Phinx\Db\Adapter\PdoAdapter) {
            $pdo = $dbAdapter->getConnection();
            $pdo->exec('SET foreign_key_checks = 1');
        }
    }
}
