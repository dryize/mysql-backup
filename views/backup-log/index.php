<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BackupLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Backup Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="backup-log-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'schedule',
            'schema',
            'artifact',
            'hash',
            'status',
            'created_at',
        ],
    ]); ?>
</div>
