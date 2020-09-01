<?php

use modava\affiliate\widgets\JsCreateModalWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\ToastrWidget;
use modava\affiliate\AffiliateModule;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\Feedback */
/* @var $form yii\widgets\ActiveForm */
$abc = \modava\affiliate\models\table\FeedbackTimeTable::getAllRecordsActive();
?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $model->toastr_key . '-form']) ?>
<div class="feedback-form">
    <?php $form = ActiveForm::begin([
        'id' => 'feedback_form',
        'enableAjaxValidation' => true,
        'validationUrl' => Url::toRoute(['/affiliate/feedback/validate', 'id' => $model->primaryKey]),
    ]); ?>

    <div class="row">
        <div class="col-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'customer_id')->dropDownList(
                ArrayHelper::map(\modava\affiliate\models\table\CustomerTable::getAllRecords(), 'id', function($model) { return $model['full_name'] . ' - ' . $model['phone']; }),
                [ 'prompt' => Yii::t('backend', 'Select an option ...'),
                    'id' => 'customer-id'
                ]
            ) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'feedback_type')->dropDownList(
                Yii::$app->getModule('affiliate')->params['feedback_type'],
                ['prompt' => Yii::t('backend', 'Select an option ...')]
            ) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'feedback_time_id')->dropDownList(
                ArrayHelper::map($abc, 'id', 'title'),
                [ 'prompt' => Yii::t('backend', 'Select an option ...'),
                    'id' => 'feedback-time-id'
                ]
            ) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'unsatisfied_reason_id')->dropDownList(
                ArrayHelper::map(\modava\affiliate\models\table\UnsatisfiedReasonTable::getAllRecordsActive(), 'id', 'title'),
                [ 'prompt' => Yii::t('backend', 'Select an option ...'),
                    'id' => 'unsatisfied-reason-id'
                ]
            ) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'satisfied_feedback')->widget(\modava\tiny\TinyMce::class, [
                'options' => ['rows' => 6],
                'type' => 'content'
            ]) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'description')->widget(\modava\tiny\TinyMce::class, [
                'options' => ['rows' => 6],
                'type' => 'content'
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?= JsCreateModalWidget::widget(['formClassName' => 'feedback_form', 'modelName' => 'Feedback']) ?>

<?php
$script = <<< JS
    function showHidePartner() {
        let delayTime = 200;
        
        switch ($('#feedback-feedback_type').val()) {
            case '0':
                $('.field-unsatisfied-reason-id').show(delayTime);
                $('.field-feedback-satisfied_feedback').hide(delayTime);
                break
            case '1':
                $('.field-unsatisfied-reason-id').hide(delayTime);
                $('.field-feedback-satisfied_feedback').show(delayTime);
                break
            default:
                $('.field-unsatisfied-reason-id').hide(delayTime);
                $('.field-feedback-satisfied_feedback').hide(delayTime);
                break;
        }
    }

    showHidePartner();
    $('#feedback-feedback_type').on('change', function() {
        showHidePartner();
    });
JS;
$this->registerJs($script, \yii\web\View::POS_END);
