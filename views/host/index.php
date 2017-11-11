<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\HostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Hosts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="host-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Host', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'tag',
            'host',
            'port',
            'username',
            // 'password',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
