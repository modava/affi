<?php

use modava\affiliate\models\Partner;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\search\CustomerSearch */
/* @var $form yii\widgets\ActiveForm */

$templateInput = [
    'template' => '{label}<div class="input-group">{input}<button type="button" class="btn btn-light clear-value"><span class="fa fa-times"></span></button></div>{error}{hint}'
];
?>

<div class="customer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 0
        ],
    ]); ?>

    <section class="hk-sec-wrapper mb-2">
        <div class="row collapse show" id="search-panel">
            <div class="col-md-3 col-sm-4 col-lg-3">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'keyword', $templateInput)
                            ->textInput(['maxlength' => true])
                            ->label(Yii::t('backend', 'Tên, SĐT'))
                            ->input('text',
                                ['placeholder' => Yii::t('backend', 'Tên, SĐT')]) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-4 col-lg-3">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'partner_id', $templateInput)->dropDownList(ArrayHelper::map(Partner::getAllRecords(), 'id', 'title'), [
                            'prompt' => Yii::t('backend', 'Chọn một giá trị ...')
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary btn-sm']) ?>
                <button class="btn btn-primary btn-sm btn-hide-search" data-toggle="collapse" data-target="#search-panel"
                        aria-expanded="false" aria-controls="search-panel" type="button"><?= Yii::t('backend',
                        'Ẩn tìm kiếm') ?></button>
            </div>
        </div>
    </section>

    <?php ActiveForm::end(); ?>

</div>
<?php

$script = <<<JS
saveStateSearchPanel('#search-panel', '.btn-hide-search', 'affiliate-customer-search-panel');
JS;

$this->registerJs($script, yii\web\View::POS_END);
