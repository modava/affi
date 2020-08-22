<?php

use modava\affiliate\widgets\JsCreateModalWidget;
use modava\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\ToastrWidget;
use modava\affiliate\AffiliateModule;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\Coupon */
/* @var $form yii\widgets\ActiveForm */
$model->expired_date = $model->expired_date != null
    ? date('d-m-Y H:i', strtotime($model->expired_date))
    : '';
?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $model->toastr_key . '-form']) ?>
<div class="coupon-form">
    <?php $form = ActiveForm::begin([
        'id' => 'coupon_form',
        'enableAjaxValidation' => true,
        'validationUrl' => Url::toRoute(['/affiliate/coupon/validate', 'id' => $model->primaryKey]),
    ]); ?>
    <div class="row">
        <div class="col-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'coupon_code')->textInput(['maxlength' => true,]) ?>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-primary" id="js-generate-coupon-code"><?=AffiliateModule::t('affiliate', 'Generate Coupon Code')?></button>
                </div>
            </div>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'quantity')->textInput() ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'expired_date')->widget(DateTimePicker::class, [
                'template' => '{input}{button}',
                'pickButtonIcon' => 'btn btn-increment btn-light',
                'pickIconContent' => Html::tag('span', '', ['class' => 'glyphicon glyphicon-th']),
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd-mm-yyyy hh:ii',
                    'todayHighLight' => true,
                ]
            ]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'customer_id')->dropDownList(
                ArrayHelper::map(\modava\affiliate\models\table\CustomerTable::getAllRecords(), 'id', function($model) { return $model['full_name'] . ' - ' . $model['phone']; }),
                [ 'prompt' => AffiliateModule::t('affiliate', 'Select an option ...'),
                    'id' => 'customer-id'
                ]
            ) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'coupon_type_id')->dropDownList(
                    ArrayHelper::map(\modava\affiliate\models\table\CouponTypeTable::getAllRecords(), 'id', 'title'),
                    [ 'prompt' => AffiliateModule::t('affiliate', 'Select an option ...') ]
            ) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'promotion_type')->dropDownList(
                Yii::$app->getModule('affiliate')->params["promotion_type"],
                [ 'prompt' => AffiliateModule::t('affiliate', 'Select an option ...'),
                    'id' => 'promotion-type'
                ]
            ) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'promotion_value')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'description')->widget(\modava\tiny\TinyMce::class, [
                'options' => ['rows' => 6],
                'type' => 'content'
            ]) ?>
        </div>
    </div>

        <div class="form-group">
            <?= Html::submitButton(AffiliateModule::t('affiliate', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>

<?= JsCreateModalWidget::widget(['formClassName' => 'coupon_form', 'modelName' => 'Coupon']) ?>

<?php
$script = <<< JS
function generateCouponCode(upperCase = false) {
    let code = Math.random(10).toString(36).substr(2);
    return upperCase ? code.toUpperCase() : code;
}

$('#js-generate-coupon-code').on('click', function() {
    $('#coupon-coupon_code').val(generateCouponCode()).trigger('change');
});

$('#coupon-coupon_code').on('change keyup blur', function() {
    $(this).val($(this).val().toUpperCase());  
});

JS;
$this->registerJs($script, \yii\web\View::POS_END);
