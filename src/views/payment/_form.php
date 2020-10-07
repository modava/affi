<?php


use kartik\select2\Select2;
use modava\affiliate\models\Payment;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use backend\widgets\ToastrWidget;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\Payment */
/* @var $form yii\widgets\ActiveForm */

if ($model->primaryKey === null) $model->status = Payment::STATUS_DRAFT;
?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $model->toastr_key . '-form']) ?>
<div class="payment-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'customer_id')->widget(Select2::class, [
                'value' => $model->customer_id,
                'initValueText' => $model->customer_id ? $model->customer->full_name : '',
                'options' => ['placeholder' => Yii::t('backend', 'Chọn một giá trị ...')],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Url::toRoute(['/affiliate/customer/get-customer-by-key-word']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(model) { return model.text; }'),
                    'templateSelection' => new JsExpression('function (model) { return model.text; }'),
                ],
            ]); ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'amount')->input('number') ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'status')->dropDownList(Yii::$app->getModule('affiliate')->params['payment_status'], [
                    'prompt' => Yii::t('backend', 'Chọn một giá trị ...')
            ]) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'description')->textarea() ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
