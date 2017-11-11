<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "schedule".
 *
 * @property integer $id
 * @property string $tag
 * @property string $type
 * @property integer $host
 * @property string $schema
 * @property string $frequency
 * @property integer $retention
 * @property string $destination
 * @property string $status
 * @property string $next
 * @property string $last_run
 * @property string $created_at
 *
 * @property BackupLog[] $backupLogs
 * @property Host $host0
 */
class Schedule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag', 'type', 'schema', 'frequency', 'retention', 'destination'], 'required'],
            [['host', 'retention'], 'integer'],
            [['next', 'last_run', 'created_at'], 'safe'],
            [['tag', 'schema', 'destination'], 'string', 'max' => 255],
            [['type', 'frequency'], 'string', 'max' => 64],
            [['status'], 'string', 'max' => 12],
            [['host'], 'exist', 'skipOnError' => true, 'targetClass' => Host::className(), 'targetAttribute' => ['host' => 'id']],
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
            'type' => 'Type',
            'host' => 'Host',
            'schema' => 'Schema',
            'frequency' => 'Frequency',
            'retention' => 'Retention',
            'destination' => 'Destination',
            'status' => 'Status',
            'next' => 'Next',
            'last_run' => 'Last Run',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBackupLogs()
    {
        return $this->hasMany(BackupLog::className(), ['schedule' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHost0()
    {
        return $this->hasOne(Host::className(), ['id' => 'host']);
    }
}
