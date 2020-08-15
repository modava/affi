<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\search\FeedbackSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="feedback-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'slug') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'unsatisfied_reason_id') ?>

    <?php // echo $form->field($model, 'feedback_time_id') ?>

    <?php // echo $form->field($model, 'feedback_type') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(AffiliateModule::t('affiliate','Search.'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(AffiliateModule::t('affiliate','Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
