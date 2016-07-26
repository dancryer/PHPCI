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
            ->addIndex('email', ['unique' => true])
            ->addIndex('name', ['unique' => true])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $user_table = $this->table('user');
        $user_table
            ->removeIndex('email', ['unique' => true])
            ->removeIndex('name', ['unique' => true])
            ->save();
    }
}
