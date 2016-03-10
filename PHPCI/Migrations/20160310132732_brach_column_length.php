<?php

use Phinx\Migration\AbstractMigration;

/**
 * Column branch in build table extended to 250 characters.
 */
class BrachColumnLength extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('build');
        $table->changeColumn('branch', 'string', array('limit' => 250));
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table('build');
        $table->changeColumn('branch', 'string', array('limit' => 50));
    }


}
