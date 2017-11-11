<?php

use yii\db\Migration;

/**
 * Class m171111_180517_backup_log
 */
class m171111_180517_backup_log extends Migration
{

    protected $_tablename = 'backup_log';
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->_tablename, [
            'id'        => $this->primaryKey(10)->unsigned(),
            'schedule'  => $this->integer(10)->unsigned(),
            'schema'    => $this->string()->notNull(),
            'artifact'  => $this->string()->notNull(),
            'hash'  => $this->string()->notNull(),
            'status'    => $this->char(120)->notNull()->defaultValue('CREATING'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->$this->dropTable($this->_tablename);
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171111_180517_backup_log cannot be reverted.\n";

        return false;
    }
    */
}
