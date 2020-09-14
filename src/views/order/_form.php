<?php

use dosamigos\datepicker\DatePicker;
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
                ['prompt' => Yii::t('backend', 'Select an option ...')]
            ) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'partner_order_code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'partner_customer_id')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'date_create')->widget(DatePicker::class, [
                'addon' => '<button type="button" class="btn btn-increment btn-light"><i class="ion ion-md-calendar"></i></button>',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true,
                ]
            ]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'status')->dropDownList(
                Yii::$app->getModule('affiliate')->params['order_status'],
                ['prompt' => Yii::t('backend', 'Select an option ...')]
            ) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'pre_total')->textInput(['maxlength' => true, 'readonly' => true]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'discount')->textInput(['maxlength' => true, 'readonly' => true]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'other_discount')->textInput(['maxlength' => true, 'readonly' => true]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'final_total')->textInput(['maxlength' => true, 'readonly' => true]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'commision_for_coupon_owner')->textInput(['maxlength' => true, 'readonly' => true]) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'description')->widget(\modava\tiny\TinyMce::class, [
                'options' => ['rows' => 6],
                'type' => 'content'
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

