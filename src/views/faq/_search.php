<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \modava\affiliate\AffiliateModule;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\search\FaqSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="faq-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        <div class="col-3">
            <?= $form->field($model, 'title')->input('text', ['placeholder' => AffiliateModule::t('affiliate', 'Place a question...')])->label(false) ?>
        </div>
        <div class="col-3">
            <?= $form->field($model, 'short_content')->input('text', ['placeholder' => AffiliateModule::t('affiliate', 'Place a short answer...')])->label(false) ?>
        </div>
        <div class="col-3">
            <?= $form->field($model, 'faq_category_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(\modava\affiliate\models\table\FaqCategoryTable::getAllRecordsPublished(), 'id', 'title'),
                'value' => $model->getAttribute('faq_category_id'),
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'options' => [
                    'placeholder' => AffiliateModule::t('affiliate', 'Faq Category')
                ],
                'theme' => Select2::THEME_BOOTSTRAP
            ])->label(false) ?>
        </div>
        <div class="col-1">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('affiliate', 'Search'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
