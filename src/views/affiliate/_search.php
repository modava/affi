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
<?php $form = ActiveForm::begin(['method' => 'GET', 'id' => 'affiliate-search', 'action' => Url::toRoute(['/affiliate'])]); ?>
<div class="hk-sec-wrapper p-1">
    <div class="row collapse show save-state-search" data-search-panel="affiliate-index-search-panel" id="search-panel">
        <div class="col-md-5 col-sm-4 col-lg-5">
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'creation_time_from')->label(Yii::t('backend',
                        'Ngày tạo - từ'))->widget(DatePicker::class, [
                        'addon' => $datapickerAddon,
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                        ],
                        'options' => [
                            'placeholder' => Yii::t('backend', 'Ngày tạo - từ')
                        ],
                    ]) ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, 'creation_time_to')->label(Yii::t('backend',
                        'Ngày tạo - đến'))->widget(DatePicker::class, [
                        'addon' => $datapickerAddon,
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                        ],
                        'options' => [
                            'placeholder' => Yii::t('backend', 'Ngày tạo - đến')
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 col-lg-3">
            <div class="row">
                <div class="col-12">
                    <?= $form->field($model, 'keyword', $templateInput)
                        ->textInput(['maxlength' => true])
                        ->label(Yii::t('backend', 'Full Name, Phone, Code'))
                        ->input('text',
                            ['placeholder' => Yii::t('backend', 'Full Name, Phone, Code')]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-lg-2">
            <div class="form-group row">
                <div class="col-12">
                    <?= $form->field($model, 'co_so', $templateInput)
                        ->label(Yii::t('backend',
                            'Cơ sở'))
                        ->dropDownList(
                            [
                                '1' => 'Cơ sở 1',
                                '2' => 'Cơ sở 2',
                            ], // $dropdowns['co_so'],

                            ['prompt' => '---' . Yii::t('backend', 'Cơ sở') . '---']
                        ) ?>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-lg-2">
            <div class="form-group row">
                <div class="col-12">
                    <?= $form->field($model, 'permission_user', $templateInput)->label(Yii::t('backend',
                        'Nhân viên'))->dropDownList(
                        $dropdowns['permission_user'],
                        ['prompt' => '---' . Yii::t('backend', 'Nhân viên') . '---']
                    ) ?>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-sm-4 col-lg-5">
            <div class="form-group row">
                <div class="col-6">
                    <?= $form->field($model, 'appointment_time_from')->label(Yii::t('backend',
                        'Lịch hẹn - từ'))->widget(DatePicker::class, [
                        'addon' => $datapickerAddon,
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                        ],
                        'options' => [
                            'placeholder' => Yii::t('backend', 'Lịch hẹn - từ')
                        ],
                    ]) ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, 'appointment_time_to')->label(Yii::t('backend',
                        'Lịch hẹn - đến'))->widget(DatePicker::class, [
                        'addon' => $datapickerAddon,
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                        ],
                        'options' => [
                            'placeholder' => Yii::t('backend', 'Lịch hẹn - đến')
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 col-lg-3">
            <div class="form-group row">
                <div class="col-12">
                    <?= $form->field($model, 'customer_come_time_to',
                        $templateInput)->label(Yii::t('backend',
                        'Trạng thái khách đến'))->dropDownList(
                        $dropdowns['customer_come_time_to'],
                        ['prompt' => '---' . Yii::t('backend', 'Trạng thái khách đến') . '---']
                    ) ?>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-lg-2">
            <div class="form-group row">
                <div class="col-12">
                    <?= $form->field($model, 'directsale', $templateInput)->label(Yii::t('backend',
                        'Direct Sales'))->dropDownList(
                        $dropdowns['directsale'],
                        ['prompt' => '---' . Yii::t('backend', 'Direct Sales') . '---']
                    ) ?>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-lg-2">
            <div class="form-group row">
                <div class="col-12">
                    <?= $form->field($model, 'nguon_online', $templateInput)->label(Yii::t('backend',
                        'Nguồn Online'))->dropDownList(
                        $dropdowns['nguon_online'],
                        ['prompt' => '---' . Yii::t('backend', 'Nguồn Online') . '---']
                    ) ?>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-sm-6 col-lg-5">
            <div class="form-group row">
                <div class="col-6">
                    <?= $form->field($model,
                        'appointment_time_from_lich_dieu_tri')->label(Yii::t('backend',
                        'Lịch điều trị - từ'))->widget(DatePicker::class, [
                        'addon' => $datapickerAddon,
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                        ],
                        'options' => [
                            'placeholder' => Yii::t('backend', 'Lịch điều trị - từ')
                        ],
                    ]) ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model,
                        'appointment_time_to_lich_dieu_tri')->label(Yii::t('backend',
                        'Lịch điều trị - đến'))->widget(DatePicker::class, [
                        'addon' => $datapickerAddon,
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                        ],
                        'options' => [
                            'placeholder' => Yii::t('backend', 'Lịch điều trị - đến')
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 col-lg-3">
            <div class="form-group row">
                <div class="col-12">
                    <?= $form->field($model, 'id_dich_vu', $templateInput)->label(Yii::t('backend',
                        'Nguồn'))->dropDownList(
                        $dropdowns['id_dich_vu'],
                        ['prompt' => '---' . Yii::t('backend', 'Nguồn') . '---']
                    ) ?>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-lg-2">
            <div class="form-group row">
                <div class="col-12">
                    <?= $form->field($model, 'thao_tac', $templateInput)->label(Yii::t('backend',
                        'Thao tác'))->dropDownList(
                        $dropdowns['thao_tac'],
                        ['prompt' => '---' . Yii::t('backend', 'Thao tác') . '---']
                    ) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn-primary btn btn-sm"><?= Yii::t('backend',
                    'Search') ?></button>
            <a href="<?= $currentRoute ?>" type="button"
               class="btn-success btn btn-sm"><?= Yii::t('backend',
                    'Default') ?></a>
            <a href="<?= $oneMonthRoute ?>" type="button"
               class="search-btn btn-info btn btn-sm"><?= Yii::t('backend', 'Customer 1 Month') ?></a>
            <a href="<?= $threeMonthsRoute ?>" type="button"
               class="search-btn btn-pink btn btn-sm"><?= Yii::t('backend', 'Customer 3 Month') ?></a>
            <a href="<?= $sixMonthsRoute ?>" type="button"
               class="search-btn btn-indigo btn btn-sm"><?= Yii::t('backend', 'Customer 6 Month') ?></a>
            <a href="<?= Url::toRoute(['clear-cache']) ?>"
               class="btn btn-link btn-sm pull-right"><?= Yii::t('backend', 'Clear Cache') ?></a>
            <button class="btn btn-primary btn-sm btn-hide-search" data-toggle="collapse" data-target="#search-panel"
                    aria-expanded="false" aria-controls="search-panel" type="button"><?= Yii::t('backend',
                    'Ẩn tìm kiếm') ?></button>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$script = <<< JS
$('#affiliate-search').on('submit', function () {
    $(this).find('[name="page"]').val(1); // Reset page to 1 before search anything
});

JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>


