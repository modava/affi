<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\search\SmsLogSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$templateInput = [
    'options' => ['class' => 'd-flex'],
    'labelOptions' => ['class' => 'w-50', 'style' => 'line-height:34px']];
$templateInputWithTemplate = $templateInput;
$templateInputWithTemplate['template'] = '{label}<div class="input-group">{input}<button type="button" class="btn btn-light clear-value hello" style="line-height:1.55"><span class="fa fa-times"></span></button></div>{error}{hint}';
$datapickerAddon = '<button type="button" class="btn btn-light clear-value" style="line-height:1.55"><span class="fa fa-times"></span></button><button type="button" class="btn btn-increment btn-light"><i class="ion ion-md-calendar"></i></button>';
?>
<div class="sms-log-search">
    
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>
    <section class="hk-sec-wrapper p-1 mb-2">
        <div class="row collapse show save-state-search" data-search-panel="affiliate-sms-log-search-panel"
             id="search-panel">
            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="col-12">
                    <?= $form->field($model, 'created_at_from',$templateInput)
                        ->label('Ngày tạo - từ')
                        ->widget(\dosamigos\datepicker\DatePicker::class, [
                            'addon' => $datapickerAddon,
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'dd-mm-yyyy',
                                'todayHighlight' => true,
                            ],
                            'options' => [
                                'placeholder' => Yii::t('backend', 'Ngày tạo - từ')
                            ],
                        ])
                    ?>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="col-12">
                    <?= $form->field($model, 'created_at_to',$templateInput)
                        ->label('Ngày tạo - đến')
                        ->widget(\dosamigos\datepicker\DatePicker::class, [
                            'addon' => $datapickerAddon,
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'dd-mm-yyyy',
                                'todayHighlight' => true,
                            ],
                            'options' => [
                                'placeholder' => Yii::t('backend', 'Ngày tạo - đến')
                            ],
                        ])
                    ?>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="col-12">
                    <?= $form->field($model, 'status',$templateInputWithTemplate)->label('Trạng thái')->dropDownList([
                        1 => 'Thành công',
                        2 => 'Thất bại',
                    ], [
                        'prompt' => 'Chọn một giá trị ...'
                    ])
                    ?>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary btn-sm']) ?>
                <button class="btn btn-primary btn-sm btn-hide-search" data-toggle="collapse"
                        data-target="#search-panel"
                        aria-expanded="false" aria-controls="search-panel" type="button"><?= Yii::t('backend',
                        'Ẩn tìm kiếm') ?></button>
            </div>
        </div>
    </section>
    
    
    <?php ActiveForm::end(); ?>

</div>
