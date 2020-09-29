<?php

use modava\affiliate\helpers\Utils;
use modava\affiliate\models\Customer;
use modava\affiliate\models\search\PartnerSearch;
use modava\affiliate\widgets\JsCreateModalWidget;
use modava\location\models\table\LocationCountryTable;
use modava\location\models\table\LocationDistrictTable;
use modava\location\models\table\LocationProvinceTable;
use modava\location\models\table\LocationWardTable;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\ToastrWidget;
use modava\affiliate\AffiliateModule;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\Customer */
/* @var $form yii\widgets\ActiveForm */

$model->birthday = Utils::convertDateToDisplayFormat($model->birthday);
$model->date_accept_do_service = Utils::convertDateToDisplayFormat($model->date_accept_do_service);
$model->date_checkin = Utils::convertDateToDisplayFormat($model->date_checkin);
$model->country_id = 237; // Viet Nam
?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $model->toastr_key . '-form']) ?>
<div class="customer-form">
    <?php $form = ActiveForm::begin([
        'id' => 'customer_form',
        'enableAjaxValidation' => true,
        'validationUrl' => Url::toRoute(['/affiliate/customer/validate', 'id' => $model->primaryKey]),
    ]); ?>

    <section class="hk-sec-wrapper mb-3">
        <h5 class="hk-sec-title">Thông tin cá nhân</h5>
        <div class="row">
            <div class="col-6 col-md-4">
                <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-6 col-md-4">
                <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-6 col-md-4">
                <?= $form->field($model, 'sex')->dropDownList(Yii::$app->getModule('affiliate')->params['sex'], [
                    'prompt' => Yii::t('backend', 'Select an option ...'),
                    'id' => 'sex'
                ]) ?>
            </div>
            <div class="col-6 col-md-4">
                <?= $form->field($model, 'birthday')->widget(DatePicker::class, [
                    'addon' => '<button type="button" class="btn btn-increment btn-light"><i class="ion ion-md-calendar"></i></button>',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true,
                        'endDate' => '+0d'
                    ]
                ]) ?>
            </div>
            <div class="col-6 col-md-4">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-6 col-md-4">
                <?= $form->field($model, 'face_customer')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-6 col-md-4">
                <?= $form->field($model, 'id_card_number')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-6 col-md-4">
                <?= $form->field($model, 'payment_type')->dropDownList(Yii::$app->getModule('affiliate')->params['customer_payment_type'], [
                    'prompt' => Yii::t('backend', 'Select an option ...'),
                    'id' => 'payment_type'
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <?= $form->field($model, 'partner_id')->dropDownList(
                    ArrayHelper::map(\modava\affiliate\models\table\PartnerTable::getAllRecords(), 'id', 'title'),
                    [
                        'prompt' => Yii::t('backend', 'Select an option ...'),
                        'id' => 'partner-id',
                    ]
                ) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'partner_customer_id')->textInput(['maxlength' => true, 'readonly' => 'true']) ?>
            </div>
        </div>

        <h6 class="hk-sec-title my-3">Thông tin tài khoản ngân hàng</h6>
        <div class="row">
            <div class="col-6 col-md-4">
                <?= $form->field($model, 'bank_name')->textInput() ?>
            </div>

            <div class="col-6 col-md-4">
                <?= $form->field($model, 'bank_branch')->textInput() ?>
            </div>

            <div class="col-6 col-md-4">
                <?= $form->field($model, 'bank_customer_id')->input('number') ?>
            </div>
        </div>
    </section>

    <section class="hk-sec-wrapper">
        <h5 class="hk-sec-title">Thông tin khác</h5>
        <div class="row">
            <div class="col-6 col-md-4 col-lg-3">
                <?php if (!$model->id) $model->status = Customer::STATUS_HOAN_THANH_DICH_VU?>
                <?= $form->field($model, 'status')->dropDownList(Yii::$app->getModule('affiliate')->params['customer_status'], [
                    'prompt' => Yii::t('backend', 'Select an option ...'),
                    'id' => 'status'
                ]) ?>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <?= $form->field($model, 'date_accept_do_service')->widget(DatePicker::class, [
                    'addon' => '<button type="button" class="btn btn-increment btn-light"><i class="ion ion-md-calendar"></i></button>',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true,
                    ]
                ]) ?>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <?= $form->field($model, 'date_checkin')->widget(DatePicker::class, [
                    'addon' => '<button type="button" class="btn btn-increment btn-light"><i class="ion ion-md-calendar"></i></button>',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true,
                    ]
                ]) ?>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <?= $form->field($model,
                    'country_id')->dropDownList(ArrayHelper::map(LocationCountryTable::getAllCountry(Yii::$app->language),
                    'id', 'CommonName'), [
                    'prompt' => Yii::t('backend', 'Chọn quốc gia...'),
                    'class' => 'form-control load-data-on-change',
                    'load-data-element' => '#select-province',
                    'load-data-url' => Url::toRoute(['/location/location-province/get-province-by-country']),
                    'load-data-key' => 'country',
                    'load-data-data' => json_encode(['option_tag' => 'true']),
                    'load-data-method' => 'GET',
                    'load-data-callback' => '$("#select-district, #select-ward").find("option[value!=\'\']").remove();',
                ]) ?>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <?= $form->field($model,
                    'province_id')->dropDownList(ArrayHelper::map(LocationProvinceTable::getProvinceByCountry($model->country_id,
                    Yii::$app->language), 'id', 'name'), [
                    'id' => 'select-province',
                    'prompt' => Yii::t('backend', 'Chọn Tỉnh/Thành phố...'),
                    'class' => 'form-control load-data-on-change',
                    'load-data-element' => '#select-district',
                    'load-data-url' => Url::toRoute(['/location/location-district/get-district-by-province']),
                    'load-data-key' => 'province',
                    'load-data-data' => json_encode(['option_tag' => 'true']),
                    'load-data-method' => 'GET',
                    'load-data-callback' => '$("#select-ward").find("option[value!=\'\']").remove();'
                ]) ?>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <?= $form->field($model,
                    'district_id')->dropDownList(ArrayHelper::map(LocationDistrictTable::getDistrictByProvince($model->province_id,
                    Yii::$app->language), 'id', 'name'), [
                    'id' => 'select-district',
                    'prompt' => Yii::t('backend', 'Chọn Quận/Huyện...'),
                    'class' => 'form-control load-data-on-change',
                    'load-data-element' => '#select-ward',
                    'load-data-url' => Url::toRoute(['/location/location-ward/get-ward-by-district']),
                    'load-data-key' => 'district',
                    'load-data-data' => json_encode(['option_tag' => 'true']),
                    'load-data-method' => 'GET',
                ]) ?>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <?= $form->field($model,
                    'ward_id')->dropDownList(ArrayHelper::map(LocationWardTable::getWardByDistrict($model->district_id),
                    'id', 'name'), [
                    'prompt' => Yii::t('backend', 'Chọn Phường/Xã...'),
                    'id' => 'select-ward',
                ]) ?>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <?= $form->field($model, 'address')->textInput() ?>
            </div>

            <div class="col-12">
                <?= $form->field($model, 'description')->widget(\modava\tiny\TinyMce::class, [
                    'options' => ['rows' => 20],
                    'type' => 'content'
                ]) ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-sm btn-success']) ?>
            </div>
        </div>

    </section>
    <?php ActiveForm::end(); ?>
</div>

<?= JsCreateModalWidget::widget(['formClassName' => 'customer_form', 'modelName' => 'Customer']) ?>

<?php
$dashBoardMyAuris = PartnerSearch::getRecordBySlug('dashboard-myauris')->id;

$script = <<< JS
function handleRequirePartnerCustomer() {
    let partner = $('#customer-partner_customer_id').closest('.form-group');
    
    if ($('#partner-id').val() === '$dashBoardMyAuris') {
        partner.show(300);
    } else {
        partner.hide(300);
    }
}

$(function () {
     handleRequirePartnerCustomer();
     $('#partner-id').on('change', function () {
         handleRequirePartnerCustomer();
     });
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>
