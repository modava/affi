<?php

use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\search\ReceiptSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$templateInput = [
    'options' => ['class' => 'd-flex'],
    'labelOptions' => ['class' => 'w-50', 'style' => 'line-height:34px']];
$templateInputWithTemplate = $templateInput;
$dateTemplateInput = $templateInput;
$templateInputWithTemplate['template'] = '{label}<div class="input-group h-90">{input}<button type="button" class="btn btn-light clear-value"><span class="fa fa-times"></span></button></div>{error}{hint}';

$datapickerAddon = '<button type="button" class="btn btn-light clear-value"><span class="fa fa-times"></span></button><button type="button" class="btn btn-increment btn-light"><i class="ion ion-md-calendar"></i></button>';
$dateTemplateInput['template'] = '{label}
                            <div class="input-group">
                                <div class="input-group" style="width: calc(100% - 51px);">{input}
                                </div>
                            </div>
                    {error}{hint}';
?>
<div class="receipt-search">
    
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>
    <section class="hk-sec-wrapper p-1">
        <div class="row collapse show save-state-search" data-search-panel="affiliate-order-search-panel"
             id="search-panel">

            <div class="col-md-4 col-sm-4 col-lg-4 mt-1">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'order', $dateTemplateInput)
                            ->label(Yii::t('backend', 'Đơn hàng'))
                            ->input('text',
                                ['placeholder' => Yii::t('backend', 'Đơn hàng')]) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-lg-4 mt-1">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'created_at', $dateTemplateInput)
                            ->widget(DateRangePicker::class, [
                                'useWithAddon' => true,
                                'convertFormat' => true,
                                'readonly' => true,
                                'options' => [
                                    'class' => 'data-krajee-daterangepicker form-control',
                                    'placeholder' => 'Từ ngày - Đến ngày'
                                ],
                                'presetDropdown' => true, // need for ranges below
                                'pluginOptions' => [
                                    'autoApply' => true,
                                    'locale' => [
                                        'format' => 'd-m-Y',
                                        'monthNames' => ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                                        'customRangeLabel' => Yii::t('backend', 'Tùy chọn ngày'),
                                    ],
                                    'showDropdowns' => true,
                                    'todayHighlight' => true,
                                    'ranges' => [
                                        Yii::t('backend', "Hôm nay") => ['"' . date('d-m-Y') . '"' , '"' .  date('d-m-Y') . '"'],
                                        Yii::t('backend', "Hôm qua") => ['"' . date('d-m-Y',strtotime("-1 days")) . '"' , '"' .  date('d-m-Y',strtotime("-1 days")). '"'],
                                        Yii::t('backend', "Tháng này") => ['"' .date('d-m-Y',strtotime('first day of this month')) . '"', '"' .  date('d-m-Y',strtotime('last day of this month')) . '"'],
                                        Yii::t('backend', "Tháng trước") => ['"' .date("d-m-Y", strtotime("first day of previous month")) . '"','"' .  date("d-m-Y", strtotime("last day of previous month")) . '"'],
                                    ],
                                ],
                            ]); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-lg-4 mt-1">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'receipt_date', $dateTemplateInput)
                            ->widget(DateRangePicker::class, [
                                'useWithAddon' => true,
                                'convertFormat' => true,
                                'readonly' => true,
                                'options' => [
                                    'class' => 'data-krajee-daterangepicker form-control',
                                    'placeholder' => 'Từ ngày thu  - Đến ngày thu'
                                ],
                                'presetDropdown' => true,
                                'pluginOptions' => [
                                    'autoApply' => true,
                                    'locale' => [
                                        'format' => 'd-m-Y',
                                        'monthNames' => ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                                        'customRangeLabel' => Yii::t('backend', 'Tùy chọn ngày'),
                                    ],
                                    'showDropdowns' => true,
                                    'todayHighlight' => true,
                                    'ranges' => [
                                        Yii::t('backend', "Hôm nay") => ['"' . date('d-m-Y') . '"' , '"' .  date('d-m-Y') . '"'],
                                        Yii::t('backend', "Hôm qua") => ['"' . date('d-m-Y',strtotime("-1 days")) . '"' , '"' .  date('d-m-Y',strtotime("-1 days")). '"'],
                                        Yii::t('backend', "Tháng này") => ['"' .date('d-m-Y',strtotime('first day of this month')) . '"', '"' .  date('d-m-Y',strtotime('last day of this month')) . '"'],
                                        Yii::t('backend', "Tháng trước") => ['"' .date("d-m-Y", strtotime("first day of previous month")) . '"','"' .  date("d-m-Y", strtotime("last day of previous month")) . '"'],
                                    ],
                                ],
                            ]); ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12">
                <?= $form->field($model, 'button')->hiddenInput(['id' => 'type-button'])->label(false) ?>
                <?php
                $receiptToday = 'btn-primary';
                $receiptSearch = 'btn-primary';
                if ($model->button == '1') {
                    $orderToday = 'btn-success';
                } ?>
                <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn ' . $receiptSearch . ' btn-sm', 'id' => 'receipt-search']) ?>
                <button class="btn btn-primary btn-sm btn-hide-search" data-toggle="collapse"
                        data-target="#search-panel"
                        aria-expanded="false" aria-controls="search-panel" type="button"><?= Yii::t('backend',
                        'Ẩn tìm kiếm') ?></button>
            </div>
        </div>
    </section>
    
    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<< JS
    $('.kv-clear').addClass('d-none');
JS;
$this->registerJs($script);

?>
