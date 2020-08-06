<?php

use modava\affiliate\helpers\Utils;
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
$model->country_id = 237; // Viet Name
?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $model->toastr_key . '-form']) ?>
<div class="customer-form">
    <?php $form = ActiveForm::begin([
        'id' => 'customer_form',
        'enableAjaxValidation' => true,
        'validationUrl' => Url::toRoute(['/affiliate/customer/validate', 'id' => $model->primaryKey]),
    ]); ?>
        <div class="row">
            <div class="col-6">
                <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'face_customer')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'sex')->dropDownList(Yii::$app->controller->module->params['sex'], [
                    'prompt' => AffiliateModule::t('affiliate', 'Select an option ...'),
                    'id' => 'sex'
                ]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'birthday')->widget(DatePicker::class, [
                    'addon' => '<button type="button" class="btn btn-increment btn-light"><i class="ion ion-md-calendar"></i></button>',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true,
                        'endDate' => '+0d'
                    ]
                ])  ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'date_accept_do_service')->widget(DatePicker::class, [
                    'addon' => '<button type="button" class="btn btn-increment btn-light"><i class="ion ion-md-calendar"></i></button>',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true,
                    ]
                ])  ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'date_checkin')->widget(DatePicker::class, [
                    'addon' => '<button type="button" class="btn btn-increment btn-light"><i class="ion ion-md-calendar"></i></button>',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true,
                    ]
                ])  ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'partner_customer_id')->textInput(['maxlength' => true, 'readonly' => 'true']) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'partner_id')->dropDownList(
                    ArrayHelper::map(\modava\affiliate\models\table\PartnerTable::getAllRecords(), 'id', 'title'),
                    [ 'prompt' => AffiliateModule::t('affiliate', 'Select an option ...'),
                        'id' => 'partner-id',
                    ]
                ) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'country_id')->dropDownList(ArrayHelper::map(LocationCountryTable::getAllCountry(Yii::$app->language), 'id', 'CommonName'), [
                    'prompt' => AffiliateModule::t('customer', 'Chọn quốc gia...'),
                    'class' => 'form-control load-data-on-change',
                    'load-data-element' => '#select-province',
                    'load-data-url' => Url::toRoute(['/location/location-province/get-province-by-country']),
                    'load-data-key' => 'country',
                    'load-data-data' => json_encode(['option_tag' => 'true']),
                    'load-data-method' => 'GET',
                    'load-data-callback' => '$("#select-district, #select-ward").find("option[value!=\'\']").remove();',
                ]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'province_id')->dropDownList(ArrayHelper::map(LocationProvinceTable::getProvinceByCountry($model->country_id, Yii::$app->language), 'id', 'name'), [
                    'id' => 'select-province',
                    'prompt' => AffiliateModule::t('customer', 'Chọn Tỉnh/Thành phố...'),
                    'class' => 'form-control load-data-on-change',
                    'load-data-element' => '#select-district',
                    'load-data-url' => Url::toRoute(['/location/location-district/get-district-by-province']),
                    'load-data-key' => 'province',
                    'load-data-data' => json_encode(['option_tag' => 'true']),
                    'load-data-method' => 'GET',
                    'load-data-callback' => '$("#select-ward").find("option[value!=\'\']").remove();'
                ]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'district_id')->dropDownList(ArrayHelper::map(LocationDistrictTable::getDistrictByProvince($model->province_id, Yii::$app->language), 'id', 'name'), [
                    'id' => 'select-district',
                    'prompt' => AffiliateModule::t('customer', 'Chọn Quận/Huyện...'),
                    'class' => 'form-control load-data-on-change',
                    'load-data-element' => '#select-ward',
                    'load-data-url' => Url::toRoute(['/location/location-ward/get-ward-by-district']),
                    'load-data-key' => 'district',
                    'load-data-data' => json_encode(['option_tag' => 'true']),
                    'load-data-method' => 'GET',
                ]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'ward_id')->dropDownList(ArrayHelper::map(LocationWardTable::getWardByDistrict($model->district_id), 'id', 'name'), [
                    'prompt' => AffiliateModule::t('customer', 'Chọn Phường/Xã...'),
                    'id' => 'select-ward',
                ]) ?>
            </div>

            <div class="col-12">
                <?= $form->field($model, 'address')->textarea( ['rows' => 2 ]) ?>
            </div>
            <div class="col-12">
                <?= $form->field($model, 'description')->widget(\modava\tiny\TinyMce::class, [
                    'options' => ['rows' => 6],
                ]) ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton(AffiliateModule::t('affiliate', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

    <?php ActiveForm::end(); ?>
</div>

<?= JsCreateModalWidget::widget(['formClassName' => 'customer_form', 'modelName' => 'Customer']) ?>