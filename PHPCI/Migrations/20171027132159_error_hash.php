<?php


use Phinx\Migration\AbstractMigration;

class ErrorHash extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $errors = $this->table('build_error');
        $errors->addColumn('hash', 'string', ['limit' => 32, 'null' => true, 'default' => null]);
        $errors->addColumn('is_new', 'boolean');
        $errors->save();
    }
}
