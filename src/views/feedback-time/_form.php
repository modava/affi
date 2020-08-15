<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\ToastrWidget;
use modava\affiliate\AffiliateModule;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\FeedbackTime */
/* @var $form yii\widgets\ActiveForm */
?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $model->toastr_key . '-form']) ?>
<div class="feedback-time-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php if (Yii::$app->controller->action->id == 'create') {
        $model->status = 1;
    } ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <?= $form->field($model, 'description')->widget(\modava\tiny\TinyMce::class, [
        'options' => ['rows' => 6],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(AffiliateModule::t('affiliate', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
