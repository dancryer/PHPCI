<?php

use Phinx\Migration\AbstractMigration;

class UniqueEmailAndNameUserFields extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $user_table = $this->table('user');
        $user_table
            ->addIndex('email', array('unique' => true))
            ->addIndex('name', array('unique' => true))
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $user_table = $this->table('user');
        $user_table
            ->removeIndex('email', array('unique' => true))
            ->removeIndex('name', array('unique' => true))
            ->save();
    }
}
