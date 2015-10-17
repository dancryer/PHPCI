<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class ErrorsTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('build_error');
        $table->addColumn('build_id', 'integer', array('signed' => true));
        $table->addColumn('plugin', 'string', array('limit' => 100));
        $table->addColumn('file', 'string', array('limit' => 250, 'null' => true));
        $table->addColumn('line_start', 'integer', array('signed' => false, 'null' => true));
        $table->addColumn('line_end', 'integer', array('signed' => false, 'null' => true));
        $table->addColumn('severity', 'integer', array('signed' => false, 'limit' => MysqlAdapter::INT_TINY));
        $table->addColumn('message', 'string', array('limit' => 250));
        $table->addColumn('created_date', 'datetime');
        $table->addIndex(array('build_id', 'created_date'), array('unique' => false));
        $table->addForeignKey('build_id', 'build', 'id', array('delete'=> 'CASCADE', 'update' => 'CASCADE'));
        $table->save();

    }
}
