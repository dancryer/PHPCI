<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class ProjectTableDefaults extends AbstractMigration
{
    public function change()
    {
        $this->table('project')
             ->changeColumn('build_config', MysqlAdapter::PHINX_TYPE_TEXT, ['null' => true])
             ->changeColumn('archived', MysqlAdapter::PHINX_TYPE_INTEGER, [
                 'length'  => MysqlAdapter::INT_TINY,
                 'default' => 0,
             ])
             ->save();
    }
}
