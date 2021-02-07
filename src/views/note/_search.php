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
/* @var $model modava\affiliate\models\search\NoteSearch */
/* @var $form yii\widgets\ActiveForm */

$templateInput = [
    'options' => ['class' => 'd-flex'],
    'labelOptions' => ['class' => 'w-50', 'style' => 'line-height:34px']];
$templateInputWithTemplate = $templateInput;
$templateInputWithTemplate['template'] = '{label}<div class="input-group">{input}<button type="button" class="btn btn-light clear-value"><span class="fa fa-times"></span></button></div>{error}{hint}';

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
            <div class="row collapse show save-state-search" data-search-panel="affiliate-note-search-panel"
                 id="search-panel">
                <div class="col-md-4 col-sm-4 col-lg-4">
                    <div class="form-group row">
                        <div class="col-12">
                            <?= $form->field($model, 'title', $templateInputWithTemplate)
                                ->textInput(['maxlength' => true])
                                ->input('text',
                                    ['placeholder' => Yii::t('backend', 'Tiêu đề')]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4">
                    <div class="form-group row">
                        <div class="col-12">
                            <?= $form->field($model, 'customer_id',$templateInput)->widget(Select2::class, [
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
                                        'delay' => 250,
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
                <div class="col-md-4 col-sm-4 col-lg-4">
                    <div class="form-group row">
                        <div class="col-12">
                            <?= $form->field($model, 'partner_id', $templateInputWithTemplate)->label('KH: Đối tác')->dropDownList(ArrayHelper::map(PartnerTable::getAllRecords(), 'id', 'title'), [
                                'prompt' => Yii::t('backend', 'Chọn một giá trị ...')
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4">

                    <div class="form-group row">
                        <div class="col-12">
                            <?= $form->field($model, 'created_at',$templateInput)->widget(DatePicker::class, [
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
                <div class="col-md-4 col-sm-4 col-lg-4">

                    <div class="form-group row">
                        <div class="col-12">
                            <?= $form->field($model, 'call_time',$templateInput)->widget(DatePicker::class, [
                                'addon' => $datapickerAddon,
                                'clientOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-mm-yyyy',
                                    'todayHighlight' => true,
                                ],
                                'options' => [
                                    'placeholder' => Yii::t('backend', 'Thời gian gọi')
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4">
                    <div class="form-group row">
                        <div class="col-12">
                            <?= $form->field($model, 'recall_time',$templateInput)->widget(DatePicker::class, [
                                'addon' => $datapickerAddon,
                                'clientOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-mm-yyyy',
                                    'todayHighlight' => true,
                                ],
                                'options' => [
                                    'placeholder' => Yii::t('backend', 'Thời gian gọi lại')
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4">
                    <div class="form-group row">
                        <div class="col-12">
                            <?= $form->field($model, 'is_recall', $templateInputWithTemplate)->dropDownList(
                                Yii::$app->getModule('affiliate')->params['note_is_recall'],
                                ['prompt' => Yii::t('backend', 'Chọn một giá trị ...')]
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <?= $form->field($model,'button')->hiddenInput(['id'=>'type-button'])->label(false) ?>
                    <?php
                    $orderToday = 'btn-primary';
                    $orderSearch = 'btn-primary';
                    if ($model->button == '1') {
                        $orderToday = 'btn-success';
                    } ?>
                    <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary btn-sm']) ?>
                    <button class="btn btn-primary btn-sm btn-hide-search" data-toggle="collapse"
                            data-target="#search-panel"
                            aria-expanded="false" aria-controls="search-panel" type="button"><?= Yii::t('backend',
                            'Ẩn tìm kiếm') ?></button>
                    <?= Html::submitButton('Hôm nay', ['class' => 'btn ' . $orderToday.' btn-sm', 'id' => 'order-today', 'tabindex' => 1]) ?>
                </div>
            </div>
        </section>
        
        <?php ActiveForm::end(); ?>

    </div>

<?php
$date = date('d-m-Y');
$script = <<< JS
    $('body').find('button[type=submit]').unbind('click').bind('click', function(e) {
    if (e.target.id == 'order-today') {
        $('#notesearch-created_at').val('$date');
        $('#type-button').val(1);
    } else {
        $('#type-button').val('');
    }
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);
