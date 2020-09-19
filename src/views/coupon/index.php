<?php

use backend\widgets\ToastrWidget;
use common\grid\MyGridView;
use modava\affiliate\widgets\DropdownWidget;
use modava\affiliate\widgets\NavbarWidgets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel modava\affiliate\models\search\CouponSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $kolsFanForm modava\affiliate\models\KolsFanForm */

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
            <a class="btn btn-outline-light btn-sm" href="<?= \yii\helpers\Url::to(['create']); ?>"
               title="<?= Yii::t('backend', 'Create'); ?>">
                <i class="fa fa-plus"></i> <?= Yii::t('backend', 'Create'); ?></a>
        </div>

        <!-- Row -->

        <?php Pjax::begin(['id' => 'coupon-gridview']); ?>
        <div class="row">
            <div class="col-xl-12">
                <?= $this->render('_search', ['model' => $searchModel]); ?>

                <section class="hk-sec-wrapper index">
                    <div class="row">
                        <div class="col-sm">
                            <div class="table-wrap">
                                <div class="dataTables_wrapper dt-bootstrap4 table-responsive">
                                    <?= MyGridView::widget([
                                        'dataProvider' => $dataProvider,
                                        'layout' => '
                                        {errors}
                                        <div class="pane-single-table">
                                            {items}
                                        </div>
                                        <div class="pager-wrap clearfix">
                                            {summary}' .
                                            Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_pageTo', [
                                                'totalPage' => $totalPage,
                                                'currentPage' => Yii::$app->request->get($dataProvider->getPagination()->pageParam)
                                            ]) .
                                            Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_pageSize') .
                                            '{pager}
                                        </div>
                                    ',
                                        'tableOptions' => [
                                            'id' => 'dataTable',
                                            'class' => 'dt-grid dt-widget pane-hScroll',
                                        ],
                                        'myOptions' => [
                                            'class' => 'dt-grid-content my-content pane-vScroll',
                                            'data-minus' => '{"0":95,"1":".hk-navbar","2":".nav-tabs","3":".hk-pg-header","4":".hk-footer-wrap"}'
                                        ],
                                        'summaryOptions' => [
                                            'class' => 'summary pull-right',
                                        ],
                                        'pager' => [
                                            'firstPageLabel' => Yii::t('receipt', 'First'),
                                            'lastPageLabel' => Yii::t('receipt', 'Last'),
                                            'prevPageLabel' => Yii::t('receipt', 'Previous'),
                                            'nextPageLabel' => Yii::t('receipt', 'Next'),
                                            'maxButtonCount' => 5,

                                            'options' => [
                                                'tag' => 'ul',
                                                'class' => 'pagination pull-left',
                                            ],

                                            // Customzing CSS class for pager link
                                            'linkOptions' => ['class' => 'page-link'],
                                            'activePageCssClass' => 'active',
                                            'disabledPageCssClass' => 'disabled page-disabled',
                                            'pageCssClass' => 'page-item',

                                            // Customzing CSS class for navigating link
                                            'prevPageCssClass' => 'paginate_button page-item prev',
                                            'nextPageCssClass' => 'paginate_button page-item next',
                                            'firstPageCssClass' => 'paginate_button page-item first',
                                            'lastPageCssClass' => 'paginate_button page-item last',
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
                                                'template' => DropdownWidget::widget([
                                                        'title' => Yii::t('t', 'Hành động'),
                                                        'dropdowns' => [
                                                            '{send-sms-to-customer}',
                                                            '{show-sms-coupon-content}',
                                                            '{update}',
                                                            '{delete}',
                                                        ],
                                                        'isCustomItem' => true,
                                                        'options' => [
                                                            'class' => 'btn-success btn-sm fs-12'
                                                        ]
                                                    ]),
                                                'headerOptions' => [
                                                    'class' => 'header-200',
                                                ],
                                                'buttons' => [
                                                    'send-sms-to-customer' => function ($url, $model) {
                                                        if (!$model->couponCanUse() || $model->count_sms_sent >= 3 || $model->customer->partner_id == 2) return '';

                                                        return Html::a('<i class="glyphicon glyphicon-send"></i> ' . Yii::t('backend', 'Gửi SMS đến KH'), 'javascript:;', [
                                                            'title' => Yii::t('backend', 'Gửi cho KH'),
                                                            'alia-label' => Yii::t('backend', 'Gửi cho KH'),
                                                            'data-pjax' => 0,
                                                            'class' => 'btn btn-success btn-xs send-sms-to-customer m-1',
                                                            'data-id' => $model->primaryKey,
                                                        ]);
                                                    },
                                                    'update' => function ($url, $model) {
                                                        if ($model->quantity_used > 0) return Html::button('Coupon đã sử dụng không được sửa', ['class' => 'btn btn-danger btn-xs my-1']);

                                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('backend', 'Update'), $url, [
                                                            'title' => Yii::t('backend', 'Update'),
                                                            'alia-label' => Yii::t('backend', 'Update'),
                                                            'data-pjax' => 0,
                                                            'class' => 'btn btn-info btn-xs m-1'
                                                        ]);
                                                    },
                                                    'delete' => function ($url, $model) {
                                                        if ($model->quantity_used > 0) return  Html::button('Coupon đã sử dụng không được xóa', ['class' => 'btn btn-danger btn-xs']);

                                                        return Html::a('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('backend', 'Delete'), 'javascript:;', [
                                                            'title' => Yii::t('backend', 'Delete'),
                                                            'class' => 'btn btn-danger btn-xs btn-del m-1',
                                                            'data-title' => Yii::t('backend', 'Delete?'),
                                                            'data-pjax' => 0,
                                                            'data-url' => $url,
                                                            'btn-success-class' => 'success-delete',
                                                            'btn-cancel-class' => 'cancel-delete',
                                                            'data-placement' => 'top'
                                                        ]);
                                                    },
                                                    'show-sms-coupon-content' => function ($url, $model) {
                                                        if ($model->customer->partner->slug !== 'kols') return '';

                                                        return '<button type="button" class="btn btn-primary btn-xs m-1" data-action="show-sms-coupon-content" data-coupon-id="' . $model->primaryKey . '" data-toggle="modal" data-target="#coupon-index-modal">Gửi SMS đến Fan</button>';
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
                                                    $content = '<strong>Tên: </strong>' . ($model->customer_id ? Html::a($model->customer->full_name, Url::toRoute(['/affiliate/customer/view', 'id' => $model->customer_id])) : '') . '<br/>';
                                                    $content .= '<strong>Đối tác: </strong>' . Html::a($model->customer->partner->title, Url::toRoute(['/affiliate/partner/view', 'id' => $model->customer->partner_id]));
                                                    return $content;
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
                </section>
            </div>
        </div>
        <?php Pjax::end(); ?>
    </div>

    <!-- The Modal -->
    <div class="modal" id="coupon-index-modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <?php $form = ActiveForm::begin(['method' => 'GET', 'id' => 'send-sms-to-fan-form', 'action' => Url::toRoute(['/affiliate/coupon/send-sms-coupon-to-fan'])]); ?>

                        <div class="row">
                            <div class="col-12">
                                <?= $form->field($kolsFanForm, 'name')->label(Yii::t('backend', 'Tên'))->textInput() ?>
                            </div>
                            <div class="col-12">
                                <?= $form->field($kolsFanForm, 'phone')->label(Yii::t('backend', 'Số điện thoại'))->textInput() ?>
                                <?= $form->field($kolsFanForm, 'coupon_id')->label(false)->input('hidden') ?>
                            </div>
                        </div>
                    <strong>Nội dung:</strong>
                        <div class="row">
                            <div class="col-12 message-conatiner"></div>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" form="send-sms-to-fan-form" value="Submit">Gửi</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                </div>

            </div>
        </div>
    </div>
<?php
$urlChangePageSize = \yii\helpers\Url::toRoute(['perpage']);
$urlSendSMS = Url::toRoute(['/affiliate/coupon/send-sms-to-customer']);
$urlGetSms = Url::toRoute(['/affiliate/coupon/get-content-sms-coupon']);
$script = <<< JS
function initPopoverSMS () {
    $('.send-sms-to-customer').popover({
        html: true,
        content: function () {
            let id = $(this).data('id');
            return $(`<div><button class="btn btn-success btn-sm popover-save" data-id="` + id + `">Xác nhận</button><button class="ml-2 btn btn-secondary btn-sm popover-cancel">Hủy</button></div>`);
        }
    })
}

initPopoverSMS();
$(document).on('pjax:complete', function() {
  initPopoverSMS()
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

$('body').on('click', '.success-delete', function(e){
    e.preventDefault();
    var url = $(this).attr('href') || null;
    if(url !== null){
        $.post(url);
    }
    return false;
});

$('#coupon-index-modal')
    .on('show.bs.modal', function (event) {
        let btnClicked = $(event.relatedTarget);
        $(this).find('[name="KolsFanForm[coupon_id]"]').val(btnClicked.data('coupon-id'));
    
        switch (btnClicked.data('action')) {
            case 'show-sms-coupon-content':
                $(this).find('.modal-title').text('Gửi đến Fan');
                $('#coupon-index-modal').find('.message-conatiner').myLoading();
                
                $.get('$urlGetSms', {coupon_id: btnClicked.data('coupon-id')}, function(response) {
                   $('#coupon-index-modal').find('.message-conatiner').html(response.success ? response.data : response.message).myUnloading();
               })
                break;
        }
    })
    .on('hide.bs.modal', function() {
        $('#send-sms-to-fan-form').find('input').val('');
    });

$('#send-sms-to-fan-form').on('beforeSubmit', function(e) {    
    e.preventDefault();
    let self = $(this);
    
    $.ajax({
            type: 'post',
            url: self.attr('action'),
            dataType: 'json',
            data: self.serialize()
        }).done(res => {
            $('#coupon-index-modal').modal('hide');
            if (res.success) {
                self.modal('hide');
                $.toast({
                    heading: 'Thông báo',
                    text: 'Thành công',
                    position: 'top-right',
                    class: 'jq-toast-success',
                    hideAfter: 6000,
                    stack: 6,
                    showHideTransition: 'fade'
                });
            }
            else {
                self.modal('hide');
                $.toast({
                    heading: 'Thông báo',
                    text: res.message,
                    position: 'top-right',
                    class: 'jq-toast-danger',
                    hideAfter: 6000,
                    stack: 6,
                    showHideTransition: 'fade'
                });
            }
        }).fail(f => {
            self.modal('hide');
            $.toast({
                heading: 'Thông báo',
                text: 'Thất bại',
                position: 'top-right',
                class: 'jq-toast-danger',
                hideAfter: 6000,
                stack: 6,
                showHideTransition: 'fade'
            });
        });

    return false;
});

var customPjax = new myGridView();
customPjax.init({
    pjaxId: '#coupon-gridview',
    urlChangePageSize: '$urlChangePageSize',
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);