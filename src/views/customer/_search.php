<?php

use modava\affiliate\models\table\PartnerTable;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\search\CustomerSearch */
/* @var $form yii\widgets\ActiveForm */

$templateInput = [
    'options' => ['class' => 'd-flex'],
    'labelOptions' => ['class' => 'w-50', 'style' => 'line-height:34px']];
$templateInputWithTemplate = $templateInput;
$templateInputWithTemplate['template'] = '{label}<div class="input-group">{input}<button type="button" class="btn btn-light clear-value"><span class="fa fa-times"></span></button></div>{error}{hint}';
?>

<div class="customer-search">
    
    <?php $form = ActiveForm::begin([
        'id' => 'customer-search',
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 0
        ],
    ]); ?>

    <section class="hk-sec-wrapper p-1">
        <div class="row collapse show save-state-search" data-search-panel="affiliate-customer-search-panel"
             id="search-panel">
            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'keyword', $templateInputWithTemplate)
                            ->textInput(['maxlength' => true])
                            ->label(Yii::t('backend', 'Tên, SĐT, CMND/CTCD'))
                            ->input('text',
                                ['placeholder' => Yii::t('backend', 'Tên, SĐT, CMND/CTCD')]) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'partner_id', $templateInputWithTemplate)->dropDownList(ArrayHelper::map(PartnerTable::getAllRecords(), 'id', 'title'), [
                            'prompt' => Yii::t('backend', 'Chọn một giá trị ...')
                        ]) ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'has_commission', $templateInputWithTemplate)
                            ->label('Tình trạng hoa hồng')->dropDownList([
                                1 => "Có hoa hồng",
                                0 => "Chưa có hoa hồng",
                            ], [
                                'prompt' => Yii::t('backend', 'Chọn một giá trị ...'),
                            ]) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'need_to_pay', $templateInputWithTemplate)->label("Tình trạng khách")->dropDownList([
                            0 => "Đã thanh toán hết",
                            1 => "Chưa thanh toán hết",
                        ], [
                            'prompt' => Yii::t('backend', 'Chọn một giá trị ...'),
                        ]) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'paid_commission', $templateInputWithTemplate)->label('Tình trạng thanh toán')->dropDownList([
                            1 => "Đã có thanh toán",
                            0 => "Chưa có thanh toán",
                        ], [
                            'prompt' => Yii::t('backend', 'Chọn một giá trị ...'),
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary btn-sm']) ?>
                <button class="btn btn-primary btn-sm btn-hide-search" data-toggle="collapse"
                        data-target="#search-panel"
                        aria-expanded="false" aria-controls="search-panel" type="button"><?= Yii::t('backend',
                        'Ẩn tìm kiếm') ?></button>
            </div>
        </div>
    </section>
    
    <?php ActiveForm::end(); ?>

</div>
