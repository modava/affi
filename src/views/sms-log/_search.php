<?php

use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\search\SmsLogSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sms-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'message') ?>

    <?= $form->field($model, 'to_number') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'response_log') ?>

    <?php // echo $form->field($model, 'request_log') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend','Search.'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend','Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
