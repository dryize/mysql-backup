<?php

use yii\db\Migration;

/**
 * Class m171111_181538_schedule
 */
class m171111_181538_schedule extends Migration
{
    protected $_tablename = 'schedule';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->_tablename, [
            'id'    => $this->primaryKey(10)->unsigned(),
            'tag'  => $this->char(255)->notNull(),
            'type' => $this->char(64)->notNull(),   //SCHEDULED, PERIODIC
            'host' => $this->integer(10)->unsigned(),
            'schema'    => $this->string()->notNull(),
            'frequency' => $this->char(64)->notNull(),   // ONCE, HOURLY, EVERY_4HOUR, DAILY
            'retention' => $this->integer(10)->notNull(),   // ONCE, HOURLY, EVERY_4HOUR, DAILY
            'destination'      => $this->string()->notNull(),
            'status'      => $this->char(12)->notNull()->defaultValue('ACTIVE'),
            'next'      => $this->timestamp()->notNull(),
            'last_run'      => $this->timestamp()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->addForeignKey('fkey-backup-schema','backup_log',
            'schedule', 'schedule', 'id');
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m171111_181538_schedule cannot be reverted.\n";
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
        echo "m171111_181538_schedule cannot be reverted.\n";

        return false;
    }
    */
}
