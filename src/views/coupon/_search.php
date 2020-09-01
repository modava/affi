<?php

use modava\affiliate\AffiliateModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\search\CouponSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-search">

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

    <?= $form->field($model, 'coupon_code') ?>

    <?= $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'expired_date') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'customer_id') ?>

    <?php // echo $form->field($model, 'coupon_type_id') ?>

    <?php // echo $form->field($model, 'quantity_used') ?>

    <?php // echo $form->field($model, 'promotion_type') ?>

    <?php // echo $form->field($model, 'promotion_value') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
