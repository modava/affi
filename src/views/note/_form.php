<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\ToastrWidget;
use modava\affiliate\AffiliateModule;
use modava\customer\components\CustomerDateTimePicker;
use modava\affiliate\widgets\JsCreateModalWidget;

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
            <?= $form->field($model, 'customer_id')->textInput() ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'call_time')->widget(CustomerDateTimePicker::class, [
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
            <?= $form->field($model, 'recall_time')->widget(CustomerDateTimePicker::class, [
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
            <?= $form->field($model, 'partner_id')->dropDownList(
                ArrayHelper::map(\modava\affiliate\models\table\PartnerTable::getAllRecords(), 'id', 'title'),
                [ 'prompt' => AffiliateModule::t('affiliate', 'Select an option ...'),
                    'id' => 'partner-id'
                ]
            ) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'description')->widget(\modava\tiny\TinyMce::class, [
                'options' => ['rows' => 6],
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(AffiliateModule::t('affiliate', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?= JsCreateModalWidget::widget(['formClassName' => 'note_form', 'modelName' => 'Note']) ?>