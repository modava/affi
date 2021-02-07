<?php

use backend\widgets\ToastrWidget;
use dosamigos\datepicker\DatePicker;
use kartik\select2\Select2;
use modava\affiliate\models\Customer;
use modava\affiliate\widgets\JsCreateModalWidget;
use modava\auth\models\User;
use modava\auth\models\UserProfile;
use modava\website\models\table\KeyValueTable;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\Coupon */
/* @var $form yii\widgets\ActiveForm */


$user = new User();
$userRole = $user->getRoleName(Yii::$app->user->id);
$model->expired_date = $model->expired_date != null
    ? date('d-m-Y', strtotime($model->expired_date))
    : '';
$disabledCommissionFor = false;
$disableUpdate = ($model->count_sms_sent > 0 || $model->quantity_used > 0) && $userRole != User::DEV;
$listPromoConf = KeyValueTable::getValueByKey('LIST_PROMO_PERCENT');
$listPromoTemp = explode(',', $listPromoConf);
$listPromo = [];
foreach ($listPromoTemp as $promo) {
    $listPromo[$promo] = $promo;
}

if ($model->primaryKey === null) {
    $defaultDateExpired = KeyValueTable::getValueByKey('COUPON_DATE_EXPIRED');
    $model->max_discount = KeyValueTable::getValueByKey('MAX_PROMO_PERCENT_VALUE');
    $model->min_discount = KeyValueTable::getValueByKey('MIN_PROMO_PERCENT_VALUE');
    $model->promotion_type = \modava\affiliate\models\Coupon::DISCOUNT_PERCENT;
    $model->expired_date = date('d-m-Y', strtotime(date('Y-m-d') . $defaultDateExpired));

    if ($userRole === 'cskh') {
        $model->commission_for = Yii::$app->user->id;
    }
} else {
    if (Yii::$app->user->id != $model->commission_for && (!Yii::$app->user->can(User::DEV) || !Yii::$app->user->can('admin'))) {
        $disabledCommissionFor = true;
    }
}
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
                <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'disabled' => $disableUpdate]) ?>
            </div>
            <div class="col-6">
                <div class="row m-0">
                    <?php if ($model->primaryKey): ?>
                        <?= $form->field($model, 'coupon_code')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>
                    <?php else: ?>
                        <div class="col-8">
                            <?= $form->field($model, 'coupon_code')->textInput(['maxlength' => true,]) ?>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn btn-primary btn-sm"
                                    id="js-generate-coupon-code"><?= Yii::t('backend', 'Generate Coupon Code') ?></button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'quantity')->input('number', ['disabled' => $disableUpdate]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'expired_date')->widget(DatePicker::class, [
                    'addon' => '<button type="button" class="btn btn-increment btn-light"><i class="ion ion-md-calendar"></i></button>',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true,
                    ],
                    'options' => [
                        'readonly' => true,
                        'disabled' => $disableUpdate
                    ]
                ]) ?>
            </div>
            <div class="col-6">
                <?php
                $initValueText = '';
                if ($model->customer_id) {
                    $customerModel = Customer::findOne($model->customer_id);
                    $initValueText = $customerModel->full_name . ' - ' . $customerModel->phone;
                }
                ?>

                <?= $form->field($model, 'customer_id')->widget(Select2::class, [
                    'value' => $model->customer_id,
                    'initValueText' => $initValueText,
                    'options' => ['placeholder' => Yii::t('backend', 'Chọn một giá trị ...'), 'disabled' => $disableUpdate],
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
            <div class="col-6">
                <?= $form->field($model, 'coupon_type_id')->dropDownList(
                    ArrayHelper::map(\modava\affiliate\models\table\CouponTypeTable::getAllRecords(), 'id', 'title'),
                    ['prompt' => Yii::t('backend', 'Select an option ...'), 'disabled' => $disableUpdate]
                ) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'promotion_type')->dropDownList(
                    Yii::$app->getModule('affiliate')->params["promotion_type"],
                    [
                        'prompt' => Yii::t('backend', 'Select an option ...'),
                        'id' => 'promotion-type',
                        'options' => [\modava\affiliate\models\Coupon::DISCOUNT_AMOUNT => ['disabled' => true]],
                        'disabled' => $disableUpdate
                    ]
                ) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'promotion_value')->textInput(['maxlength' => true, 'type' => 'number', 'readonly' => $model->quantity_used > 0]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'commission_for_owner')->textInput(['maxlength' => true, 'readonly' => 'readonly']) ?>
            </div>
            <div class="col-3">
                <?= $form->field($model, 'min_discount')->textInput(['maxlength' => true, 'readonly' => 'readonly']) ?>
            </div>
            <div class="col-3">
                <?= $form->field($model, 'max_discount')->dropDownList(
                        $listPromo,
                    [ 'prompt' => Yii::t('backend', 'Select an option ...'), ]
                ) ?>
            </div>
            <div class="col-3">
                <?= $form->field($model, 'commission_for')->widget(Select2::class, [
                    'data' => ArrayHelper::map(User::getUserByRole('cskh', [User::tableName() . '.id', UserProfile::tableName() . '.fullname']), 'id', 'fullname'),
                    'options' => ['placeholder' => Yii::t('backend', 'Chọn một giá trị ...'), 'disabled' => $disabledCommissionFor],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
            </div>

            <div class="col-12">
                <?= $form->field($model, 'description')->widget(\modava\tiny\TinyMce::class, [
                    'options' => ['rows' => 20],
                    'type' => 'content'
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

<?= JsCreateModalWidget::widget(['formClassName' => 'coupon_form', 'modelName' => 'Coupon']) ?>

<?php
$controllerURL = Url::toRoute(['/affiliate/coupon/generate-code']);
$script = <<< JS
function generateCouponCode(customerId, upperCase = false) {
    return new Promise((resolve, reject) => {
        $.get('$controllerURL?customer_id=' + customerId, function(result, status, xhr) {
            debugger;
            if (status === 'success') {
                if (result.success) {
                    resolve(result.data);
                }
            }
            reject();
        });
    });
}

function generateCouponCodeRandom() {
    let code = '';
    for (i = 0; i< 10;i ++) {
        code += String.fromCharCode(getRandomInt(65, 90));
    }
    return code;
}

function calcCommissionForOwner() {
    let value = $('#coupon_form [name="Coupon[promotion_value]"]').val() ? parseFloat($('#coupon_form [name="Coupon[promotion_value]"]').val()) : 0;
    $('#coupon_form [name="Coupon[commission_for_owner]"]').val(parseFloat($('#coupon_form [name="Coupon[max_discount]"]').val()) - value);
}

$('#coupon_form #js-generate-coupon-code').on('click', function() {
    /* Comment because change logic for short code*/
    /*let customerId = $('#coupon_form').find('[name="Coupon[customer_id]"]').val();
    if (!customerId) {
        $.toast({
            heading: 'Thông báo',
            text: 'Không có Khách hàng được chọn',
            position: 'top-right',
            class: 'jq-toast-warning',
            hideAfter: 2000,
            stack: 6,
            showHideTransition: 'fade'
        });
        return ;
    }
    
    let loading = $('#coupon_form').closest('.modal-dialog').find('.refresh-container');
    
    loading.show();
    
    if (customerId) {
        generateCouponCode(customerId).then((data) => {
            $('#coupon-coupon_code').val(data).trigger('change');
            loading.fadeOut(300);
        })
    }*/
    $('#coupon-coupon_code').val(generateCouponCodeRandom(true)).trigger('change');
});

$('#coupon_form #coupon-coupon_code').on('change keyup blur', function() {
    $(this).val($(this).val().toUpperCase());  
});

$('#coupon_form [name="Coupon[promotion_value]"], #coupon_form [name="Coupon[max_discount]"]').on('change', function () {    
    calcCommissionForOwner();
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);
