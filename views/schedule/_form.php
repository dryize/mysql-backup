<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Schedule */
/* @var $form yii\widgets\ActiveForm */

$stores = [];

foreach (\Yii::$app->params['stores'] as $store=> $val){
    $stores[$store] = $store;
}
?>

<div class="schedule-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-6">
    <?= $form->field($model, 'tag')->textInput(['maxlength' => true]) ?>
    </div>


    <div class="col-md-6">
    <?= $form->field($model, 'schema')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'host')->dropDownList(
            ArrayHelper::map(\app\models\Host::find()->all(),'id','tag'),
            ['prompt'=>'Select Host']
        )?>
    </div>


    <div class="col-md-4">
    <?= $form->field($model, 'type')->dropDownList([
        'SCHEDULED' => 'SCHEDULED',
        'PERIODIC'  => 'PERIODIC',
    ]) ?>
    </div>


    <div class="col-md-4">
    <?= $form->field($model, 'frequency')->dropDownList([
            'ONCE'          => 'ONCE',
            'HOURLY'        => 'HOURLY',
            'EVERY_4HOUR'   => 'EVERY_4HOUR',
            'DAILY'         => 'DAILY',
    ]) ?>
    </div>

    <div class="col-md-3">
    <?= $form->field($model, 'retention')->textInput() ?>
    </div>


    <div class="col-md-9">
        <?= $form->field($model, 'destination')->dropDownList($stores) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
