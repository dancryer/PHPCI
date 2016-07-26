<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class ErrorsTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('build_error');
        $table->addColumn('build_id', 'integer', ['signed' => true]);
        $table->addColumn('plugin', 'string', ['limit' => 100]);
        $table->addColumn('file', 'string', ['limit' => 250, 'null' => true]);
        $table->addColumn('line_start', 'integer', ['signed' => false, 'null' => true]);
        $table->addColumn('line_end', 'integer', ['signed' => false, 'null' => true]);
        $table->addColumn('severity', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY]);
        $table->addColumn('message', 'string', ['limit' => 250]);
        $table->addColumn('created_date', 'datetime');
        $table->addIndex(['build_id', 'created_date'], ['unique' => false]);
        $table->addForeignKey('build_id', 'build', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);
        $table->save();

    }
}
