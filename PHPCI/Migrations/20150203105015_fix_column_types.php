<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class FixColumnTypes extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        // Update the build log column to MEDIUMTEXT:
        $build = $this->table('build');
        $build->changeColumn('log', 'text', array(
            'null' => true,
            'default' => '',
            'limit' => MysqlAdapter::TEXT_MEDIUM,
        ));

        // Update the build meta value column to MEDIUMTEXT:
        $buildMeta = $this->table('build_meta');
        $buildMeta->changeColumn('meta_value', 'text', array(
            'null' => false,
            'limit' => MysqlAdapter::TEXT_MEDIUM,
        ));
    }
}
