<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class ProjectTableDefaults extends AbstractMigration
{
    public function change()
    {
        $this->table('project')
             ->changeColumn('build_config', MysqlAdapter::PHINX_TYPE_TEXT, array('null' => true))
             ->changeColumn('archived', MysqlAdapter::PHINX_TYPE_INTEGER, array(
                 'length' => MysqlAdapter::INT_TINY,
                 'default' => 0,
             ))
             ->save();
    }
}
