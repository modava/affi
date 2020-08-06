<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\ToastrWidget;
use modava\affiliate\AffiliateModule;
use \yii\helpers\ArrayHelper;
use \modava\affiliate\models\table\CouponTable;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $model->toastr_key . '-form']) ?>
<div class="order-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'coupon_id')->dropDownList(
                    ArrayHelper::map(CouponTable::getAll(), 'id', 'coupon_code'),
                    [ 'prompt' => AffiliateModule::t('affiliate', 'Select an option ...') ]
            ) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'pre_total')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'discount')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'final_total')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'description')->widget(\modava\tiny\TinyMce::class, [
                'options' => ['rows' => 6],
            ]) ?>
        </div>
    </div>
        <div class="form-group">
            <?= Html::submitButton(AffiliateModule::t('affiliate', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>

