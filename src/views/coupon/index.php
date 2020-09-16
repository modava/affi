<?php

use modava\affiliate\AffiliateModule;
use modava\affiliate\widgets\NavbarWidgets;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\widgets\ToastrWidget;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel modava\affiliate\models\search\CouponSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Coupon');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $searchModel->toastr_key . '-index']) ?>
    <div class="container-fluid px-xxl-25 px-xl-10">
        <?= NavbarWidgets::widget(); ?>

        <!-- Title -->
        <div class="hk-pg-header">
            <h4 class="hk-pg-title"><span class="pg-title-icon"><span
                            class="ion ion-md-apps"></span></span><?= Html::encode($this->title) ?>
            </h4>
            <a class="btn btn-outline-light" href="<?= \yii\helpers\Url::to(['create']); ?>"
               title="<?= Yii::t('backend', 'Create'); ?>">
                <i class="fa fa-plus"></i> <?= Yii::t('backend', 'Create'); ?></a>
        </div>

        <!-- Row -->
        <div class="row">
            <div class="col-xl-12">
                <section class="hk-sec-wrapper">

                    <?php Pjax::begin(['id' => 'coupon-gridview']); ?>
                    <div class="row">
                        <div class="col-sm">
                            <div class="table-wrap">
                                <div class="dataTables_wrapper dt-bootstrap4 table-responsive">
                                    <?= GridView::widget([
                                        'dataProvider' => $dataProvider,
                                        'layout' => '
                                        {errors}
                                        <div class="row">
                                            <div class="col-sm-12">
                                                {items}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-5">
                                                <div class="dataTables_info" role="status" aria-live="polite">
                                                    {pager}
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-7">
                                                <div class="dataTables_paginate paging_simple_numbers">
                                                    {summary}
                                                </div>
                                            </div>
                                        </div>
                                    ',
                                        'pager' => [
                                            'firstPageLabel' => Yii::t('backend', 'First'),
                                            'lastPageLabel' => Yii::t('backend', 'Last'),
                                            'prevPageLabel' => Yii::t('backend', 'Previous'),
                                            'nextPageLabel' => Yii::t('backend', 'Next'),
                                            'maxButtonCount' => 5,

                                            'options' => [
                                                'tag' => 'ul',
                                                'class' => 'pagination',
                                            ],

                                            // Customzing CSS class for pager link
                                            'linkOptions' => ['class' => 'page-link'],
                                            'activePageCssClass' => 'active',
                                            'disabledPageCssClass' => 'disabled page-disabled',
                                            'pageCssClass' => 'page-item',

                                            // Customzing CSS class for navigating link
                                            'prevPageCssClass' => 'paginate_button page-item',
                                            'nextPageCssClass' => 'paginate_button page-item',
                                            'firstPageCssClass' => 'paginate_button page-item',
                                            'lastPageCssClass' => 'paginate_button page-item',
                                        ],
                                        'columns' => [
                                            [
                                                'class' => 'yii\grid\SerialColumn',
                                                'header' => 'STT',
                                                'headerOptions' => [
                                                    'width' => 60,
                                                    'rowspan' => 2
                                                ],
                                                'filterOptions' => [
                                                    'class' => 'd-none',
                                                ],
                                            ],
                                            [
                                                'class' => 'yii\grid\ActionColumn',
                                                'header' => Yii::t('backend', 'Actions'),
                                                'template' => '{send-sms-to-customer} {update} {delete}',
                                                'headerOptions' => [
                                                    'class' => 'header-200',
                                                ],
                                                'buttons' => [
                                                    'send-sms-to-customer' => function ($url, $model) {
                                                        if (!$model->couponCanUse() || $model->count_sms_sent >= 3) return '';

                                                        return Html::a('<i class="glyphicon glyphicon-send"></i>', 'javascript:;', [
                                                            'title' => Yii::t('backend', 'Gửi cho KH'),
                                                            'alia-label' => Yii::t('backend', 'Gửi cho KH'),
                                                            'data-pjax' => 0,
                                                            'class' => 'btn btn-success btn-xs send-sms-to-customer',
                                                            'data-id' => $model->primaryKey,
                                                        ]);
                                                    },
                                                    'update' => function ($url, $model) {
                                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                                            'title' => Yii::t('backend', 'Update'),
                                                            'alia-label' => Yii::t('backend', 'Update'),
                                                            'data-pjax' => 0,
                                                            'class' => 'btn btn-info btn-xs'
                                                        ]);
                                                    },
                                                    'delete' => function ($url, $model) {
                                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'javascript:;', [
                                                            'title' => Yii::t('backend', 'Delete'),
                                                            'class' => 'btn btn-danger btn-xs btn-del',
                                                            'data-title' => Yii::t('backend', 'Delete?'),
                                                            'data-pjax' => 0,
                                                            'data-url' => $url,
                                                            'btn-success-class' => 'success-delete',
                                                            'btn-cancel-class' => 'cancel-delete',
                                                            'data-placement' => 'top'
                                                        ]);
                                                    }
                                                ],
                                            ],
                                            [
                                                'attribute' => 'title',
                                                'format' => 'raw',
                                                'value' => function ($model) {
                                                    return Html::a($model->title, ['view', 'id' => $model->id], [
                                                        'title' => $model->title,
                                                        'data-pjax' => 0,
                                                    ]);
                                                },
                                                'headerOptions' => [
                                                    'class' => 'header-200',
                                                ],
                                            ],
                                            'coupon_code',
                                            [
                                                'attribute' => 'count_sms_sent',
                                                'contentOptions' => [
                                                    'class' => 'text-right'
                                                ]
                                            ],
                                            [
                                                'attribute' => 'customer_id',
                                                'format' => 'raw',
                                                'value' => function ($model) {
                                                    return $model->customer_id ? Html::a($model->customer->full_name, Url::toRoute(['/affiliate/customer/view', 'id' => $model->customer_id])) : '';
                                                },
                                                'headerOptions' => [
                                                    'class' => 'header-200',
                                                ],
                                            ],
                                            [
                                                'attribute' => 'quantity',
                                                'contentOptions' => [
                                                    'class' => 'text-right'
                                                ]
                                            ],
                                            [
                                                'attribute' => 'quantity_used',
                                                'contentOptions' => [
                                                    'class' => 'text-right'
                                                ]
                                            ],
                                            [
                                                'attribute' => 'expired_date',
                                                'value' => function ($model) {
                                                    return $model->expired_date
                                                        ? date('d-m-Y', strtotime($model->expired_date))
                                                        : '';
                                                }
                                            ],
                                            [
                                                'attribute' => 'promotion_type',
                                                'value' => function ($model) {
                                                    return Yii::t('backend', Yii::$app->getModule('affiliate')->params["promotion_type"][$model->promotion_type]);
                                                },
                                                'headerOptions' => [
                                                    'class' => 'header-200',
                                                ],
                                            ],
                                            [
                                                'attribute' => 'min_discount',
                                                'contentOptions' => [
                                                    'class' => 'text-right'
                                                ]
                                            ],
                                            [
                                                'attribute' => 'max_discount',
                                                'contentOptions' => [
                                                    'class' => 'text-right'
                                                ]
                                            ],
                                            [
                                                'attribute' => 'promotion_value',
                                                'contentOptions' => [
                                                    'class' => 'text-right'
                                                ]
                                            ],
                                            [
                                                'attribute' => 'commission_for_owner',
                                                'contentOptions' => [
                                                    'class' => 'text-right'
                                                ]
                                            ],
                                            [
                                                'attribute' => 'created_by',
                                                'value' => 'userCreated.userProfile.fullname',
                                                'headerOptions' => [
                                                    'class' => 'header-200',
                                                ],
                                            ],
                                            [
                                                'attribute' => 'created_at',
                                                'format' => 'date',
                                                'headerOptions' => [
                                                    'class' => 'header-200',
                                                ],
                                            ],
                                        ],
                                    ]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php Pjax::end(); ?>
                </section>
            </div>
        </div>
    </div>
<?php
$urlSendSMS = Url::toRoute(['/affiliate/coupon/send-sms-to-customer']);
$script = <<< JS
function initSendSMS () {
    $('.send-sms-to-customer').popover({
        html: true,
        content: function () {
            let id = $(this).data('id');
            return $(`<div><button class="btn btn-success btn-sm popover-save" data-id="` + id + `">Xác nhận</button><button class="ml-2 btn btn-secondary btn-sm popover-cancel">Hủy</button></div>`);
        }
    })
    
    $('body').on('click', '.popover-cancel', function (e) {
        let popover = $(this).closest('.popover');
        popover.popover('hide');
    });
    $('body').on('click', '.popover-save', function (e) {
        let popup = $(this).closest('.popover');
        popup.myLoading({size: 'sm'});
         $.get('$urlSendSMS', {id: $(this).data('id')}, function(response) {
               popup.popover('hide');
               popup.myUnloading({size: 'sm'});
               if (response.success) {
                   $.toast({
                       heading: 'Thông báo',
                       text: response.message,
                       position: 'top-right',
                       class: 'jq-toast-success',
                       hideAfter: 2000,
                       stack: 6,
                       showHideTransition: 'fade'
                   });
                   $.pjax.reload({container:'#coupon-gridview', url: window.location.href});
               } else {
                   $.toast({
                       heading: 'Thông báo',
                       text: response.message,
                       position: 'top-right',
                       class: 'jq-toast-warning',
                       hideAfter: 2000,
                       stack: 6,
                       showHideTransition: 'fade'
                   });
               }
           })
    });
}

initSendSMS();
$(document).on('pjax:complete', function() {
  initSendSMS()
})

$('body').on('click', '.success-delete', function(e){
    e.preventDefault();
    var url = $(this).attr('href') || null;
    if(url !== null){
        $.post(url);
    }
    return false;
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);