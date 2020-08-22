<?php

use dosamigos\datepicker\DatePicker;
use modava\affiliate\AffiliateModule;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $model */
/* @var $dropdowns */

$currentRoute = Url::toRoute(['/' . Yii::$app->requestedRoute]);
$currentDate = date('d-m-Y');
$timestapmtCurrentDate = strtotime($currentDate);
$current1Month = date("d-m-Y", strtotime("-1 month", $timestapmtCurrentDate));
$current3Months = date("d-m-Y", strtotime("-3 month", $timestapmtCurrentDate));
$current6Months = date("d-m-Y", strtotime("-6 month", $timestapmtCurrentDate));
$oneMonthRoute = Url::toRoute([
    '/' . Yii::$app->requestedRoute,
    'ClinicSearch[appointment_time_from_lich_dieu_tri]' => "$current1Month",
    'ClinicSearch[appointment_time_to_lich_dieu_tri]' => "$current1Month",
]);
$threeMonthsRoute = Url::toRoute([
    '/' . Yii::$app->requestedRoute,
    'ClinicSearch[appointment_time_from_lich_dieu_tri]' => "$current3Months",
    'ClinicSearch[appointment_time_to_lich_dieu_tri]' => "$current3Months",
]);
$sixMonthsRoute = Url::toRoute([
    '/' . Yii::$app->requestedRoute,
    'ClinicSearch[appointment_time_from_lich_dieu_tri]' => "$current6Months",
    'ClinicSearch[appointment_time_to_lich_dieu_tri]' => "$current6Months"
]);

$templateInput = [
    'template' => '{label}<div class="input-group">{input}<button type="button" class="btn btn-light clear-value"><span class="fa fa-times"></span></button></div>{error}{hint}'
];
$datapickerAddon = '<button type="button" class="btn btn-light clear-value"><span class="fa fa-times"></span></button><button type="button" class="btn btn-increment btn-light"><i class="ion ion-md-calendar"></i></button>';
?>
<?php $form = ActiveForm::begin(['method' => 'GET']); ?>
<div class="hk-sec-wrapper">
    <div class="row collapse show" id="search-panel">
        <div class="col-md-5 col-sm-4 col-lg-5">
            <div class="form-group row">
                <div class="col-6">
                    <?= $form->field($model, 'creation_time_from')->label(AffiliateModule::t('affiliate',
                        'Ngày tạo - từ'))->widget(DatePicker::class, [
                        'addon' => $datapickerAddon,
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                        ],
                        'options' => [
                            'placeholder' => AffiliateModule::t('affiliate', 'Ngày tạo - từ')
                        ],
                    ]) ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, 'creation_time_to')->label(AffiliateModule::t('affiliate',
                        'Ngày tạo - đến'))->widget(DatePicker::class, [
                        'addon' => $datapickerAddon,
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                        ],
                        'options' => [
                            'placeholder' => AffiliateModule::t('affiliate', 'Ngày tạo - đến')
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 col-lg-3">
            <div class="form-group row">
                <div class="col-12">
                    <?= $form->field($model, 'keyword', $templateInput)
                        ->textInput(['maxlength' => true])
                        ->label(AffiliateModule::t('affiliate', 'Full Name, Phone, Code'))
                        ->input('text',
                            ['placeholder' => AffiliateModule::t('affiliate', 'Full Name, Phone, Code')]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-lg-2">
            <div class="form-group row">
                <div class="col-12">
                    <?= $form->field($model, 'co_so', $templateInput)
                        ->label(AffiliateModule::t('affiliate',
                            'Cơ sở'))
                        ->dropDownList(
                            [
                                '1' => 'Cơ sở 1',
                                '2' => 'Cơ sở 2',
                            ], // $dropdowns['co_so'],

                            ['prompt' => '---' . AffiliateModule::t('affiliate', 'Cơ sở') . '---']
                        ) ?>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-lg-2">
            <div class="form-group row">
                <div class="col-12">
                    <?= $form->field($model, 'permission_user', $templateInput)->label(AffiliateModule::t('affiliate',
                        'Nhân viên'))->dropDownList(
                        $dropdowns['permission_user'],
                        ['prompt' => '---' . AffiliateModule::t('affiliate', 'Nhân viên') . '---']
                    ) ?>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-sm-4 col-lg-5">
            <div class="form-group row">
                <div class="col-6">
                    <?= $form->field($model, 'appointment_time_from')->label(AffiliateModule::t('affiliate',
                        'Lịch hẹn - từ'))->widget(DatePicker::class, [
                        'addon' => $datapickerAddon,
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                        ],
                        'options' => [
                            'placeholder' => AffiliateModule::t('affiliate', 'Lịch hẹn - từ')
                        ],
                    ]) ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, 'appointment_time_to')->label(AffiliateModule::t('affiliate',
                        'Lịch hẹn - đến'))->widget(DatePicker::class, [
                        'addon' => $datapickerAddon,
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                        ],
                        'options' => [
                            'placeholder' => AffiliateModule::t('affiliate', 'Lịch hẹn - đến')
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 col-lg-3">
            <div class="form-group row">
                <div class="col-12">
                    <?= $form->field($model, 'customer_come_time_to',
                        $templateInput)->label(AffiliateModule::t('affiliate',
                        'Trạng thái khách đến'))->dropDownList(
                        $dropdowns['customer_come_time_to'],
                        ['prompt' => '---' . AffiliateModule::t('affiliate', 'Trạng thái khách đến') . '---']
                    ) ?>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-lg-2">
            <div class="form-group row">
                <div class="col-12">
                    <?= $form->field($model, 'directsale', $templateInput)->label(AffiliateModule::t('affiliate',
                        'Direct Sales'))->dropDownList(
                        $dropdowns['directsale'],
                        ['prompt' => '---' . AffiliateModule::t('affiliate', 'Direct Sales') . '---']
                    ) ?>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-lg-2">
            <div class="form-group row">
                <div class="col-12">
                    <?= $form->field($model, 'nguon_online', $templateInput)->label(AffiliateModule::t('affiliate',
                        'Nguồn Online'))->dropDownList(
                        $dropdowns['nguon_online'],
                        ['prompt' => '---' . AffiliateModule::t('affiliate', 'Nguồn Online') . '---']
                    ) ?>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-sm-6 col-lg-5">
            <div class="form-group row">
                <div class="col-6">
                    <?= $form->field($model,
                        'appointment_time_from_lich_dieu_tri')->label(AffiliateModule::t('affiliate',
                        'Lịch điều trị - từ'))->widget(DatePicker::class, [
                        'addon' => $datapickerAddon,
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                        ],
                        'options' => [
                            'placeholder' => AffiliateModule::t('affiliate', 'Lịch điều trị - từ')
                        ],
                    ]) ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model,
                        'appointment_time_to_lich_dieu_tri')->label(AffiliateModule::t('affiliate',
                        'Lịch điều trị - đến'))->widget(DatePicker::class, [
                        'addon' => $datapickerAddon,
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                        ],
                        'options' => [
                            'placeholder' => AffiliateModule::t('affiliate', 'Lịch điều trị - đến')
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 col-lg-3">
            <div class="form-group row">
                <div class="col-12">
                    <?= $form->field($model, 'id_dich_vu', $templateInput)->label(AffiliateModule::t('affiliate',
                        'Nguồn'))->dropDownList(
                        $dropdowns['id_dich_vu'],
                        ['prompt' => '---' . AffiliateModule::t('affiliate', 'Nguồn') . '---']
                    ) ?>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-lg-2">
            <div class="form-group row">
                <div class="col-12">
                    <?= $form->field($model, 'thao_tac', $templateInput)->label(AffiliateModule::t('affiliate',
                        'Thao tác'))->dropDownList(
                        $dropdowns['thao_tac'],
                        ['prompt' => '---' . AffiliateModule::t('affiliate', 'Thao tác') . '---']
                    ) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn-primary btn btn-sm"><?= AffiliateModule::t('affiliate',
                    'Search') ?></button>
            <a href="<?= $currentRoute ?>" type="button"
               class="btn-success btn btn-sm"><?= AffiliateModule::t('affiliate',
                    'Default') ?></a>
            <a href="<?= $oneMonthRoute ?>" type="button"
               class="search-btn btn-info btn btn-sm"><?= AffiliateModule::t('affiliate', 'Customer 1 Month') ?></a>
            <a href="<?= $threeMonthsRoute ?>" type="button"
               class="search-btn btn-pink btn btn-sm"><?= AffiliateModule::t('affiliate', 'Customer 3 Month') ?></a>
            <a href="<?= $sixMonthsRoute ?>" type="button"
               class="search-btn btn-indigo btn btn-sm"><?= AffiliateModule::t('affiliate', 'Customer 6 Month') ?></a>
            <a href="<?= Url::toRoute(['clear-cache']) ?>"
               class="btn btn-link btn-sm pull-right"><?= AffiliateModule::t('affiliate', 'Clear Cache') ?></a>
            <button class="btn btn-primary btn-sm btn-hide-search" data-toggle="collapse" data-target="#search-panel"
                    aria-expanded="false" aria-controls="search-panel" type="button"><?= AffiliateModule::t('affiliate',
                    'Ẩn tìm kiếm') ?></button>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$script = <<< JS
 $('.clear-value').on('click', function(e) {
   e.stopImmediatePropagation();
   $(this).closest('.input-group').find('input, select').val('').trigger('change');
 });

saveStateSearchPanel('#search-panel', '.btn-hide-search', 'show-affiliate-search-panel');

JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>


