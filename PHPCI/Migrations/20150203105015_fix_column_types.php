<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class FixColumnTypes extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        // Update the build log column to MEDIUMTEXT:
        $build = $this->table('build');
        $build->changeColumn('log', 'text', [
            'null'  => true,
            'limit' => MysqlAdapter::TEXT_MEDIUM,
        ]);

        // Update the build meta value column to MEDIUMTEXT:
        $buildMeta = $this->table('build_meta');
        $buildMeta->changeColumn('meta_value', 'text', [
            'null'  => false,
            'limit' => MysqlAdapter::TEXT_MEDIUM,
        ]);
    }
}
