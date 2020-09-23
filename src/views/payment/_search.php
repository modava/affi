<?php

use dosamigos\datepicker\DatePicker;
use kartik\select2\Select2;
use modava\affiliate\models\table\PartnerTable;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\search\PaymentSearch */
/* @var $form yii\widgets\ActiveForm */


$templateInput = [
    'template' => '{label}<div class="input-group">{input}<button type="button" class="btn btn-light clear-value"><span class="fa fa-times"></span></button></div>{error}{hint}'
];
$datapickerAddon = '<button type="button" class="btn btn-light clear-value"><span class="fa fa-times"></span></button><button type="button" class="btn btn-increment btn-light"><i class="ion ion-md-calendar"></i></button>';
?>

    <div class="coupon-search">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                'data-pjax' => 1
            ],
        ]); ?>

        <section class="hk-sec-wrapper p-1 mb-2">
            <div class="row collapse show save-state-search" data-search-panel="affiliate-payment-search-panel" id="search-panel">
                <div class="col-md-3 col-sm-4 col-lg-3">
                    <div class="form-group row">
                        <div class="col-12">
                            <?= $form->field($model, 'title', $templateInput)
                                ->textInput(['maxlength' => true])
                                ->input('text',
                                    ['placeholder' => Yii::t('backend', 'Tiêu đề')]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-lg-3">
                    <div class="form-group row">
                        <div class="col-12">
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
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-lg-3">
                    <div class="form-group row">
                        <div class="col-12">
                            <?= $form->field($model, 'partner_id', $templateInput)->label('KH: Đối tác')->dropDownList(ArrayHelper::map(PartnerTable::getAllRecords(), 'id', 'title'), [
                                'prompt' => Yii::t('backend', 'Chọn một giá trị ...')
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-lg-3">
                    <div class="form-group row">
                        <div class="col-12">
                            <?= $form->field($model, 'created_at')->widget(DatePicker::class, [
                                'addon' => $datapickerAddon,
                                'clientOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-mm-yyyy',
                                    'todayHighlight' => true,
                                ],
                                'options' => [
                                    'placeholder' => Yii::t('backend', 'Ngày tạo')
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-lg-3">
                    <div class="form-group row">
                        <div class="col-12">
                            <?= $form->field($model, 'status', $templateInput)->dropDownList(
                                Yii::$app->getModule('affiliate')->params['payment_status'],
                                ['prompt' => Yii::t('backend', 'Chọn một giá trị ...')]
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary btn-sm']) ?>
                    <button class="btn btn-primary btn-sm btn-hide-search" data-toggle="collapse" data-target="#search-panel"
                            aria-expanded="false" aria-controls="search-panel" type="button"><?= Yii::t('backend',
                            'Ẩn tìm kiếm') ?></button>
                </div>
            </div>
        </section>

        <?php ActiveForm::end(); ?>

    </div>

<?php
