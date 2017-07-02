<?php

use Phinx\Migration\AbstractMigration;

class RemoveUniqueNameIndex extends AbstractMigration
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
        $user = $this->table('user');

        if ($user->hasIndex('name', array('unique' => true))) {
            $user->removeIndex('name', array('unique' => true));
            $user->save();
        }

        $user->addIndex('name', array('unique' => false));
        $user->save();
    }
}
