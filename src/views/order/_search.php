<?php

use kartik\export\ExportMenu;
use kartik\select2\Select2;
use modava\auth\models\User;
use modava\auth\models\UserProfile;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\search\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $dataProvider yii\data\ActiveDataProvider */

$templateInput = [
    'options' => ['class' => 'd-flex'],
    'labelOptions' => ['class' => 'w-50', 'style' => 'line-height:34px']];
$templateInputWithTemplate = $templateInput;
$templateInputWithTemplate['template'] = '{label}<div class="input-group">{input}<button type="button" class="btn btn-light clear-value"><span class="fa fa-times"></span></button></div>{error}{hint}';
$datapickerAddon = '<button type="button" class="btn btn-light clear-value"><span class="fa fa-times"></span></button><button type="button" class="btn btn-increment btn-light"><i class="ion ion-md-calendar"></i></button>';
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
        <div class="row collapse show save-state-search" data-search-panel="affiliate-order-search-panel"
             id="search-panel">
            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'status', $templateInput)->widget(Select2::class, [
                            'data' => Yii::$app->getModule('affiliate')->params['order_status'],
                            'options' => ['placeholder' => Yii::t('backend', 'Chọn một giá trị ...'), 'multiple' => true],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'id_customer', $templateInput)->label("Khách hàng")->widget(Select2::class, [
                            'value' => $model->id_customer,
                            'initValueText' => $model->id_customer ? \modava\affiliate\models\Customer::findOne($model->id_customer)->full_name : '',
                            'options' => ['placeholder' => Yii::t('backend', 'Chọn một giá trị ...')],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 3,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                ],
                                'ajax' => [
                                    'url' => \yii\helpers\Url::toRoute(['/affiliate/customer/get-customer-by-key-word']),
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
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'coupon', $templateInputWithTemplate)
                            ->label(Yii::t('backend', 'Mã Coupon'))
                            ->input('text',
                                ['placeholder' => Yii::t('backend', 'Mã Coupon')]) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'order_date_from', $templateInput)->label('Đơn hàng - từ')
                            ->widget(\dosamigos\datepicker\DatePicker::class, [
                                'addon' => $datapickerAddon,
                                'clientOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-mm-yyyy',
                                    'todayHighlight' => true,
                                ],
                                'options' => [
                                    'placeholder' => Yii::t('backend', 'Đơn hàng - từ')
                                ],
                            ]) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'order_date_to', $templateInput)->label('Đơn hàng - đến')
                            ->widget(\dosamigos\datepicker\DatePicker::class, [
                                'addon' => $datapickerAddon,
                                'clientOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-mm-yyyy',
                                    'todayHighlight' => true,
                                ],
                                'options' => [
                                    'placeholder' => Yii::t('backend', 'Đơn hàng - đến')
                                ],
                            ]) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'partner_order_code', $templateInputWithTemplate)
                            ->label(Yii::t('backend', 'Mã đơn hàng Partner'))
                            ->input('text',
                                ['placeholder' => Yii::t('backend', 'Mã đơn hàng Partner')]) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'date_approval_reception_from', $templateInput)->label('Ngày duyệt - từ')
                            ->widget(\dosamigos\datepicker\DatePicker::class, [
                                'addon' => $datapickerAddon,
                                'clientOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-mm-yyyy',
                                    'todayHighlight' => true,
                                ],
                                'options' => [
                                    'placeholder' => Yii::t('backend', 'Ngày duyệt - từ')
                                ],
                            ]) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'date_approval_reception_to', $templateInput)->label('Ngày duyệt - đến')
                            ->widget(\dosamigos\datepicker\DatePicker::class, [
                                'addon' => $datapickerAddon,
                                'clientOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-mm-yyyy',
                                    'todayHighlight' => true,
                                ],
                                'options' => [
                                    'placeholder' => Yii::t('backend', 'Ngày duyệt - đến')
                                ],
                            ]) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'coupon_of_sales', $templateInput)->widget(Select2::class, [
                            'data' => ArrayHelper::map(User::getUserByRole('cskh', [User::tableName() . '.id', UserProfile::tableName() . '.fullname']), 'id', 'fullname'),
                            'options' => ['placeholder' => Yii::t('backend', 'Chọn một giá trị ...')],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'created_at_from', $templateInput)->label('Ngày tạo - từ')
                            ->widget(\dosamigos\datepicker\DatePicker::class, [
                                'addon' => $datapickerAddon,
                                'clientOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-mm-yyyy',
                                    'todayHighlight' => true,
                                ],
                                'options' => [
                                    'placeholder' => Yii::t('backend', 'Ngày tạo - từ')
                                ],
                            ]) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-lg-4">
                <div class="form-group row">
                    <div class="col-12">
                        <?= $form->field($model, 'created_at_to', $templateInput)->label('Ngày tạo - đến')
                            ->widget(\dosamigos\datepicker\DatePicker::class, [
                                'addon' => $datapickerAddon,
                                'clientOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-mm-yyyy',
                                    'todayHighlight' => true,
                                ],
                                'options' => [
                                    'placeholder' => Yii::t('backend', 'Ngày tạo - đến')
                                ],
                            ]) ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12">
                <?= $form->field($model, 'button')->hiddenInput(['id' => 'type-button'])->label(false) ?>
                <?php
                $orderToday = 'btn-primary';
                $orderSearch = 'btn-primary';
                if ($model->button == '1') {
                    $orderToday = 'btn-success';
                } ?>
                <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn ' . $orderSearch . ' btn-sm', 'id' => 'order-search']) ?>
                <button class="btn btn-primary btn-sm btn-hide-search" data-toggle="collapse"
                        data-target="#search-panel"
                        aria-expanded="false" aria-controls="search-panel" type="button"><?= Yii::t('backend',
                        'Ẩn tìm kiếm') ?></button>
                <?= Html::submitButton('Hôm nay', ['class' => 'btn ' . $orderToday . ' btn-sm', 'id' => 'order-today', 'tabindex' => 1]) ?>
                <?= ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumns,
                    'filename' => 'Affiliate myauris',
                    'fontAwesome' => true,
                    'target' => ExportMenu::TARGET_SELF,
                    'showConfirmAlert' => false,
                    'clearBuffers' => true,
                    'initProvider' => true,
                    'dropdownOptions' => [
                        'label' => 'Export All',
                        'class' => 'btn btn-primary btn-sm ml-5'
                    ],
                    'columnSelectorOptions' => [
                        'class' => 'btn btn-primary btn-sm'
                    ],
                    'onRenderSheet' => function ($sheet, $widget) use ($dataProvider, $total) {
                        $exportType = $widget->getExportType();
                        $model = new \modava\affiliate\models\search\OrderSearch();
                        if ($exportType == ExportMenu::FORMAT_EXCEL_X || $exportType == ExportMenu::FORMAT_CSV || $exportType == ExportMenu::FORMAT_EXCEL) {
                            $styleArray = [
                                'font' => [
                                    'bold' => true,
                                ],
                                'alignment' => [
                                    'horizontal' => 'center',
                                ]
                            ];
                            $row = 1;
                            $alphaColumn = $model->getArrayColumn();
                            $totalRecord = $dataProvider->getTotalCount();
                            $sheet->setTitle('Báo cáo doanh thu');
                            $sheet->insertNewRowBefore(1);
                            $sheet->mergeCells($alphaColumn['partner_name'] . $row . ':' . $alphaColumn['status'] . $row);
                            $sheet->setCellValue($alphaColumn['partner_name'] . $row, 'Báo cáo doanh thu');
                            $sheet->getStyle('A1')->applyFromArray($styleArray);
                            // +4 , +5 vì có thêm phần header, title
                            $sheet->setCellValue($alphaColumn['pre_total'] . ($totalRecord + 4), "Doanh thu");
                            $sheet->setCellValue($alphaColumn['pre_total'] . ($totalRecord + 5), "Thưởng(2%)");
                            $sheet->setCellValue($alphaColumn['final_total'] . ($totalRecord + 4), $total['sumFinalTotal']);
                            $sheet->setCellValue($alphaColumn['final_total'] . ($totalRecord + 5), $total['sumFinalTotal'] * 2 / 100);
                            $sheet->getStyle($alphaColumn['pre_total'] . ($totalRecord + 4))->getFont()->setBold(true);
                            $sheet->getStyle($alphaColumn['pre_total'] . ($totalRecord + 3))->getFont()->setBold(true);
                            $sheet->getStyle($alphaColumn['pre_total'] . ($totalRecord + 5))->getFont()->setBold(true);
                            $cellValue = $sheet->rangeToArray($alphaColumn['status'] . '3:' . $alphaColumn['status'] . ($totalRecord + 2));
                            $arr = [];
                            foreach ($cellValue as $key => $value) {
                                $arr[$key + 3] = $value[0];
                            }
                            // Tô đỏ đã thu + đơn chưa hoàn thành
                            foreach ($arr as $key => $value) {
                                if ($value == 'Chưa hoàn thành') {
                                    $sheet->getStyle($alphaColumn['partner_receipted'] . $key)->getFont()->getColor()->setARGB('ff0000');
                                    $sheet->getStyle($alphaColumn['status'] . $key)->getFont()->getColor()->setARGB('ff0000');
                                }
                            }
                            
                        }
                    }
                ]);
                ?>
            </div>
        </div>
    </section>
    
    
    <?php ActiveForm::end(); ?>

</div>
<?php
$date = date('d-m-Y');
$script = <<< JS
    $('body').find('button[type=submit]').unbind('click').bind('click', function(e) {
    if (e.target.id == 'order-today') {
        $('#ordersearch-created_at_from').val('$date');
        $('#ordersearch-created_at_to').val('$date');
        $('#type-button').val(1);
    } else {
        $('#type-button').val('');
    }
});
    $('#w0-cols').addClass('d-none');
JS;
$this->registerJs($script, \yii\web\View::POS_END);

?>
