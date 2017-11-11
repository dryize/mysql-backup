<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "backup_log".
 *
 * @property integer $id
 * @property integer $schedule
 * @property string $schema
 * @property string $artifact
 * @property string $hash
 * @property string $status
 * @property string $created_at
 *
 * @property Schedule $schedule0
 */
class BackupLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'backup_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['schedule'], 'integer'],
            [['schema', 'artifact'], 'required'],
            [['hash'], 'string'],
            [['created_at'], 'safe'],
            [['schema', 'artifact'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 120],
            [['schedule'], 'exist', 'skipOnError' => true, 'targetClass' => Schedule::className(), 'targetAttribute' => ['schedule' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'schedule' => 'Schedule',
            'schema' => 'Schema',
            'artifact' => 'Artifact',
            'hash' => 'Hash',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedule0()
    {
        return $this->hasOne(Schedule::className(), ['id' => 'schedule']);
    }
}
