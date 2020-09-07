<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\ToastrWidget;
use modava\affiliate\AffiliateModule;
use modava\datetime\DateTimePicker;
use modava\affiliate\widgets\JsCreateModalWidget;
use \modava\affiliate\models\table\PartnerTable;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\Note */
/* @var $form yii\widgets\ActiveForm */

$model->call_time = $model->call_time != null
    ? date('d-m-Y H:i', strtotime($model->call_time))
    : '';
$model->recall_time = $model->recall_time != null
    ? date('d-m-Y H:i', strtotime($model->recall_time))
    : '';

?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $model->toastr_key . '-form']) ?>
    <div class="note-form">
        <?php $form = ActiveForm::begin([
            'id' => 'note_form',
            'enableAjaxValidation' => true,
            'validationUrl' => Url::toRoute(['/affiliate/note/validate', 'id' => $model->primaryKey]),
        ]); ?>

        <div class="row">
            <div class="col-6">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'customer_id')->dropDownList(
                    ArrayHelper::map(\modava\affiliate\models\table\CustomerTable::getAllRecords(), 'id',
                        function ($model) {
                            return $model['full_name'] . ' - ' . $model['phone'];
                        }),
                    [
                        'prompt' => Yii::t('backend', 'Select an option ...'),
                        'id' => 'customer-id'
                    ]
                ) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'call_time')->widget(DateTimePicker::class, [
                    'template' => '{input}{button}',
                    'pickButtonIcon' => 'btn btn-increment btn-light',
                    'pickIconContent' => Html::tag('span', '', ['class' => 'glyphicon glyphicon-th']),
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy hh:ii',
                        'todayHighLight' => true,
                    ]
                ]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'recall_time')->widget(DateTimePicker::class, [
                    'template' => '{input}{button}',
                    'pickButtonIcon' => 'btn btn-increment btn-light',
                    'pickIconContent' => Html::tag('span', '', ['class' => 'glyphicon glyphicon-th']),
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy hh:ii',
                        'todayHighLight' => true,
                    ]
                ]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'is_recall')->checkbox() ?>
            </div>
            <div class="col-12">
                <?php
                $noteTypeParams = [
                    'prompt' => Yii::t('backend', 'Select an options...'),
                ];
                if ($model->partner_note_id) {
                    $noteTypeParams['disabled'] = 'disabled';
                }
                ?>
                <?= $form->field($model, 'note_type')->dropDownList(
                    Yii::$app->getModule('affiliate')->params['note_type'],
                    $noteTypeParams
                ) ?>
            </div>
            <div class="col-6">
                <?php
                $partnerIdParams = [
                    'prompt' => Yii::t('backend', 'Select an options...'),
                ];
                if ($model->partner_note_id) {
                    $partnerIdParams['disabled'] = 'disabled';
                }
                ?>
                <?= $form->field($model, 'partner_id')->dropDownList(
                    ArrayHelper::map(PartnerTable::getAllRecords(), 'id', 'title'),
                    $partnerIdParams
                ) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'partner_note_id')->input('string', ['readonly' => 'true']) ?>
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

<?= JsCreateModalWidget::widget(['formClassName' => 'note_form', 'modelName' => 'Note']) ?>

<?php
$script = <<< JS
    function showHidePartner() {
        let conditionEffect = $('#note-note_type').val() === "1";
        let delayTime = 200;
        
         if (conditionEffect) {
            $('.field-note-partner_id').show(delayTime);
            $('.field-note-partner_note_id').show(delayTime);
        }
        else {
            $('.field-note-partner_id').hide();
            $('.field-note-partner_note_id').hide(delayTime);
        }
    }

    showHidePartner();

    $('#note-note_type').on('change', function() {
        showHidePartner();
    });
JS;
$this->registerJs($script, \yii\web\View::POS_END);
