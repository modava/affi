<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\ToastrWidget;
use modava\affiliate\AffiliateModule;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\Faq */
/* @var $form yii\widgets\ActiveForm */
?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $model->toastr_key . '-form']) ?>
<div class="faq-form">
    <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-12">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'publish')->checkbox() ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'faq_category_id')->dropDownList(
                    ArrayHelper::map(\modava\affiliate\models\table\FaqCategoryTable::getAllRecordsPublished(), 'id', 'title'),
                    [ 'prompt' => AffiliateModule::t('affiliate', 'Select an option ...') ]
                ) ?>
            </div>
            <div class="col-12">
                <?= $form->field($model, 'short_content')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-12">
                <?= $form->field($model, 'content')->widget(\modava\tiny\TinyMce::class, [
                    'options' => ['rows' => 6],
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton(AffiliateModule::t('affiliate', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
