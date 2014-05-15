<?php

use Phinx\Migration\AbstractMigration;

class BuildAgentsMigration extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $build = $this->table('build');

        // Add parent/child relationship:
        $build->addColumn('parent_id', 'integer', array('null' => true, 'default' => null));
        $build->addForeignKey('parent_id', 'build', 'id', array('delete'=> 'CASCADE', 'update' => 'CASCADE'));

        // Add PHP version column:
        $build->addColumn('engine', 'string', array('limit' => 50, 'null' => true, 'default' => null));

        $build->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $build = $this->table('build');

        $build->dropForeignKey('parent_id');
        $build->removeColumn('parent_id');
        $build->removeColumn('engine');

        $build->save();
    }
}