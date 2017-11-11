<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>MySQL Backup Manager!</h1>

    </div>

    <div class="body-content">

        <div class="row">
            <p>Backup Schedules: <?= \app\models\Schedule::find()->count() ?></p>
            <p>Backups: <?= \app\models\BackupLog::find()->count() ?></p>
            <p>Last Processed: <?= \Yii::$app->cache->get('last_run') ?></p>
        </div>

    </div>
</div>
