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
        $build->changeColumn('project_id', 'integer', array('null' => false));
        $build->changeColumn('commit_id', 'string', array('limit' => 50, 'null' => false));
        $build->changeColumn('status', 'integer', array('null' => false));
        $build->changeColumn('log', 'text', array('null' => true, 'default' => ''));
        $build->changeColumn('branch', 'string', array('limit' => 50, 'null' => false, 'default' => 'master'));
        $build->changeColumn('created', 'datetime', array('null' => true));
        $build->changeColumn('started', 'datetime', array('null' => true));
        $build->changeColumn('finished', 'datetime', array('null' => true));
        $build->changeColumn('committer_email', 'string', array('limit' => 512, 'null' => true));
        $build->changeColumn('commit_message', 'text', array('null' => true));
        $build->changeColumn('extra', 'text', array('null' => true));

        $buildMeta = $this->table('build_meta');
        $buildMeta->changeColumn('project_id', 'integer', array('null' => false));
        $buildMeta->changeColumn('build_id', 'integer', array('null' => false));
        $buildMeta->changeColumn('meta_key', 'string', array('limit' => 250, 'null' => false));
        $buildMeta->changeColumn('meta_value', 'text', array('null' => false));

        $project = $this->table('project');
        $project->changeColumn('title', 'string', array('limit' => 250, 'null' => false));
        $project->changeColumn('reference', 'string', array('limit' => 250, 'null' => false));
        $project->changeColumn('branch', 'string', array('limit' => 50, 'null' => false, 'default' => 'master'));
        $project->changeColumn('ssh_private_key', 'text', array('null' => true, 'default' => null));
        $project->changeColumn('ssh_public_key', 'text', array('null' => true, 'default' => null));
        $project->changeColumn('type', 'string', array('limit' => 50, 'null' => false));
        $project->changeColumn('access_information', 'string', array('limit' => 250, 'null' => true, 'default' => null));
        $project->changeColumn('last_commit', 'string', array('limit' => 250, 'null' => true, 'default' => null));
        $project->changeColumn('ssh_public_key', 'text', array('null' => true, 'default' => null));
        $project->changeColumn('allow_public_status', 'integer', array('null' => false, 'default' => 0));

        $user = $this->table('user');
        $user->changeColumn('email', 'string', array('limit' => 250, 'null' => false));
        $user->changeColumn('hash', 'string', array('limit' => 250, 'null' => false));
        $user->changeColumn('is_admin', 'integer', array('null' => false, 'default' => 0));
        $user->changeColumn('name', 'string', array('limit' => 250, 'null' => false));

        if ($dbAdapter instanceof \Phinx\Db\Adapter\PdoAdapter) {
            $pdo = $dbAdapter->getConnection();
            $pdo->exec('SET foreign_key_checks = 1');
        }
    }
}
