<?php

use Phinx\Migration\AbstractMigration;

/**
 * Initial migration to create a PHPCI v1.2 database.
 */
class InitialMigration extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        // Set up tables:
        $this->createBuildTable();
        $this->createBuildMetaTable();
        $this->createProjectTable();
        $this->createUserTable();

        // Set up foreign keys:
        $build = $this->table('build');

        if (!$build->hasForeignKey('project_id')) {
            $build->addForeignKey('project_id', 'project', 'id', array('delete'=> 'CASCADE', 'update' => 'CASCADE'));
        }

        $build->save();

        $buildMeta = $this->table('build_meta');

        if (!$buildMeta->hasForeignKey('build_id')) {
            $buildMeta->addForeignKey('build_id', 'build', 'id', array('delete'=> 'CASCADE', 'update' => 'CASCADE'));
        }

        if (!$buildMeta->hasForeignKey('project_id')) {
            $buildMeta->addForeignKey('project_id', 'project', 'id', array('delete'=> 'CASCADE', 'update' => 'CASCADE'));
        }

        $buildMeta->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }

    protected function createBuildTable()
    {
        $table = $this->table('build');

        if (!$this->hasTable('build')) {
            $table->create();
        }

        if (!$table->hasColumn('project_id')) {
            $table->addColumn('project_id', 'integer');
        }

        if (!$table->hasColumn('commit_id')) {
            $table->addColumn('commit_id', 'string', array('limit' => 50));
        }

        if (!$table->hasColumn('status')) {
            $table->addColumn('status', 'integer', array('limit' => 4));
        }

        if (!$table->hasColumn('log')) {
            $table->addColumn('log', 'text');
        }

        if (!$table->hasColumn('branch')) {
            $table->addColumn('branch', 'string', array('limit' => 50));
        }

        if (!$table->hasColumn('created')) {
            $table->addColumn('created', 'datetime');
        }

        if (!$table->hasColumn('started')) {
            $table->addColumn('started', 'datetime');
        }

        if (!$table->hasColumn('finished')) {
            $table->addColumn('finished', 'datetime');
        }

        if (!$table->hasColumn('committer_email')) {
            $table->addColumn('committer_email', 'string', array('limit' => 250));
        }

        if (!$table->hasColumn('commit_message')) {
            $table->addColumn('commit_message', 'text');
        }

        if (!$table->hasColumn('extra')) {
            $table->addColumn('extra', 'text');
        }

        if ($table->hasColumn('plugins')) {
            $table->removeColumn('plugins');
        }

        if (!$table->hasIndex(array('project_id'))) {
            $table->addIndex(array('project_id'));
        }

        if (!$table->hasIndex(array('status'))) {
            $table->addIndex(array('status'));
        }

        $table->save();
    }

    protected function createBuildMetaTable()
    {
        $table = $this->table('build_meta');

        if (!$this->hasTable('build_meta')) {
            $table->create();
        }

        if (!$table->hasColumn('project_id')) {
            $table->addColumn('project_id', 'integer');
        }

        if (!$table->hasColumn('build_id')) {
            $table->addColumn('build_id', 'integer');
        }

        if (!$table->hasColumn('meta_key')) {
            $table->addColumn('meta_key', 'string', array('limit' => 250));
        }

        if (!$table->hasColumn('meta_value')) {
            $table->addColumn('meta_value', 'text');
        }

        if (!$table->hasIndex(array('build_id', 'meta_key'))) {
            $table->addIndex(array('build_id', 'meta_key'));
        }

        $table->save();
    }

    protected function createProjectTable()
    {
        $table = $this->table('project');

        if (!$this->hasTable('project')) {
            $table->create();
        }

        if (!$table->hasColumn('title')) {
            $table->addColumn('title', 'string', array('limit' => 250));
        }

        if (!$table->hasColumn('reference')) {
            $table->addColumn('reference', 'string', array('limit' => 250));
        }

        if (!$table->hasColumn('git_key')) {
            $table->addColumn('git_key', 'text');
        }

        if (!$table->hasColumn('public_key')) {
            $table->addColumn('public_key', 'text');
        }

        if (!$table->hasColumn('type')) {
            $table->addColumn('type', 'string', array('limit' => 50));
        }

        if (!$table->hasColumn('access_information')) {
            $table->addColumn('access_information', 'string', array('limit' => 250));
        }

        if (!$table->hasColumn('last_commit')) {
            $table->addColumn('last_commit', 'string', array('limit' => 250));
        }

        if (!$table->hasColumn('build_config')) {
            $table->addColumn('build_config', 'text');
        }

        if (!$table->hasColumn('allow_public_status')) {
            $table->addColumn('allow_public_status', 'integer');
        }

        if ($table->hasColumn('token')) {
            $table->removeColumn('token');
        }

        if (!$table->hasIndex(array('title'))) {
            $table->addIndex(array('title'));
        }

        $table->save();
    }

    protected function createUserTable()
    {
        $table = $this->table('user');

        if (!$this->hasTable('user')) {
            $table->create();
        }

        if (!$table->hasColumn('email')) {
            $table->addColumn('email', 'string', array('limit' => 250));
        }

        if (!$table->hasColumn('hash')) {
            $table->addColumn('hash', 'string', array('limit' => 250));
        }

        if (!$table->hasColumn('name')) {
            $table->addColumn('name', 'string', array('limit' => 250));
        }

        if (!$table->hasColumn('is_admin')) {
            $table->addColumn('is_admin', 'integer');
        }

        if (!$table->hasIndex(array('email'))) {
            $table->addIndex(array('email'));
        }

        $table->save();
    }
}
