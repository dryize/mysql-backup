<?php

use yii\db\Migration;

/**
 * Class m171111_180643_config
 */
class m171111_180643_config extends Migration
{

    protected $_tablename = 'config';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->_tablename, [
            'id'    => $this->primaryKey(10)->unsigned(),
            'name'  => $this->char(255)->notNull()->unique(),
            'value' => $this->string()->notNull(),
        ]);
        $this->createIndex('idx-name',$this->_tablename, 'name');
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
        echo "m171111_180643_config cannot be reverted.\n";

        return false;
    }
    */
}
