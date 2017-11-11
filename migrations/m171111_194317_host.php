<?php

use yii\db\Migration;

/**
 * Class m171111_194317_host
 */
class m171111_194317_host extends Migration
{
    protected $_tablename = 'host';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->_tablename, [
            'id'    => $this->primaryKey(10)->unsigned(),
            'tag'  => $this->char(255)->notNull(),
            'host'  => $this->string()->notNull(),
            'port'  => $this->integer(4)->notNull(),
            'username' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
        ]);
        $this->addForeignKey('fkey-schedule-host','schedule', 'host',
            'host', 'id');
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->_tablename);
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171111_194317_host cannot be reverted.\n";

        return false;
    }
    */
}
