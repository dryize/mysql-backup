<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "host".
 *
 * @property integer $id
 * @property string $tag
 * @property string $host
 * @property integer $port
 * @property string $username
 * @property string $password
 *
 * @property Schedule[] $schedules
 */
class Host extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'host';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag', 'host', 'port', 'username', 'password'], 'required'],
            [['port'], 'integer'],
            [['tag', 'host', 'username', 'password'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tag' => 'Tag',
            'host' => 'Host',
            'port' => 'Port',
            'username' => 'Username',
            'password' => 'Password',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedules()
    {
        return $this->hasMany(Schedule::className(), ['host' => 'id']);
    }
}
