<?php

use backend\widgets\ToastrWidget;
use modava\affiliate\AffiliateModule;
use modava\affiliate\widgets\NavbarWidgets;
use modava\affiliate\widgets\JsUtils;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use modava\affiliate\models\search\PartnerSearch;
use \modava\affiliate\models\table\CustomerTable;
use modava\affiliate\models\Note;
use \modava\affiliate\models\Coupon;
use \modava\affiliate\helpers\Utils;
use modava\affiliate\helpers\AffiliateDisplayHelper;

/* @var $dropdowns */
/* @var $dataProvider */

$this->title = Yii::t('backend', 'Customer');
$this->params['breadcrumbs'][] = $this->title;
$myAuris = PartnerSearch::getRecordBySlug('dashboard-myauris');
Yii::$app->getModule('affiliate')->params['partner_id']['dashboard-myauris'] = $myAuris->primaryKey;

?>

<?= ToastrWidget::widget(['key' => 'toastr-affiliate-list']) ?>

    <div class="container-fluid px-xxl-25 px-xl-10">
        <?= NavbarWidgets::widget(); ?>

        <!-- Row -->
        <div class="row">
            <div class="col-xl-12">
                <?= $this->render('_search', ['dropdowns' => $dropdowns, 'model' => $model]); ?>
            </div>

            <div class="col-xl-12">
                <section class="hk-sec-wrapper">
                    <div class="row">
                        <div class="col-sm">
                            <div class="table-wrap">
                                <div class="dataTables_wrapper dt-bootstrap4 ">
                                    <?= GridView::widget([
                                        'dataProvider' => $dataProvider,
                                        'layout' => '
                                        {errors}
                                        <div class="row mb-2">
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
                                        <div class="row">
                                            <div class="col-sm-12 table-responsive">
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
                                                'template' => '{dashboard-call-log} {create-customer} {create-coupon} {create-call-note} {create-feedback} {hidden-input-customer-partner-info} {hidden-input-customer-info}',
                                                'buttons' => [
                                                    'dashboard-call-log' => function ($url, $model) {
                                                        return Html::a('<i class="fa fa-history"></i>', 'javascript:;', [
                                                            'title' => Yii::t('backend', 'Lịch sử cuộc gọi dashboard'),
                                                            'alia-label' => Yii::t('backend', 'Lịch sử cuộc gọi dashboard'),
                                                            'data-model' => 'Customer',
                                                            'data-pjax' => 0,
                                                            'data-partner' => 'myaris',
                                                            'data-phone' => $model['phone'],
                                                            'class' => 'btn btn-info btn-xs show-call-log m-1'
                                                        ]);
                                                    },
                                                    'create-coupon' => function ($url, $model) {
                                                        if (!Utils::isReleaseObject('Coupon')) return '';

                                                        if (CustomerTable::getRecordByPartnerInfoFromCache(Yii::$app->getModule('affiliate')->params['partner_id']['dashboard-myauris'], $model['id'])) {
                                                            return Html::a('<i class="icon dripicons-ticket"></i>', 'javascript:;', [
                                                                'title' => Yii::t('backend', 'Create Coupon'),
                                                                'alia-label' => Yii::t('backend', 'Create Coupon'),
                                                                'data-pjax' => 0,
                                                                'data-partner' => 'myaris',
                                                                'class' => 'btn btn-info btn-xs create-coupon m-1'
                                                            ]);
                                                        }

                                                        return '';

                                                    },
                                                    'create-call-note' => function ($url, $model) {
                                                        if (CustomerTable::getRecordByPartnerInfoFromCache(Yii::$app->getModule('affiliate')->params['partner_id']['dashboard-myauris'], $model['id'])) {
                                                            return Html::a('<i class="icon dripicons-to-do"></i>', 'javascript:;', [
                                                                'title' => Yii::t('backend', 'Create Call Note'),
                                                                'alia-label' => Yii::t('backend', 'Create Call Note'),
                                                                'data-pjax' => 0,
                                                                'data-partner' => 'myaris',
                                                                'class' => 'btn btn-success btn-xs create-call-note m-1'
                                                            ]);
                                                        }

                                                        return '';

                                                    },
                                                    'create-feedback' => function ($url, $model) {
                                                        if (CustomerTable::getRecordByPartnerInfoFromCache(Yii::$app->getModule('affiliate')->params['partner_id']['dashboard-myauris'], $model['id'])) {
                                                            return Html::a('<span class="material-icons" style="font-size: 12px">feedback</span>', 'javascript:;', [
                                                                'title' => Yii::t('backend', 'Create Feedback'),
                                                                'alia-label' => Yii::t('backend', 'Create Feedback'),
                                                                'data-pjax' => 0,
                                                                'data-partner' => 'myaris',
                                                                'class' => 'btn btn-success btn-xs create-feedback m-1'
                                                            ]);
                                                        }

                                                        return '';

                                                    },
                                                    'create-customer' => function ($url, $model) {
                                                        $record = CustomerTable::getRecordByPartnerInfoFromCache(Yii::$app->getModule('affiliate')->params['partner_id']['dashboard-myauris'], $model['id']);
                                                        if ($record) {
                                                            $message = Yii::t('backend', 'Detail');

                                                            return Html::a('<i class="glyphicon glyphicon-eye-open"></i>',
                                                                Url::toRoute(['/affiliate/customer/view', 'id' => $record['id']]),
                                                                [
                                                                    'title' => $message,
                                                                    'alia-label' => $message,
                                                                    'data-pjax' => 0,
                                                                    'data-partner' => 'myaris',
                                                                    'class' => 'btn btn-primary btn-xs m-1',
                                                                    'target' => '_blank'
                                                                ]);
                                                        } else {
                                                            $message = Yii::t('backend', 'Convert');

                                                            return Html::a('<span class="glyphicon glyphicon-arrow-right"></span>', 'javascript:;', [
                                                                'title' => $message,
                                                                'alia-label' => $message,
                                                                'data-pjax' => 0,
                                                                'data-partner' => 'myaris',
                                                                'class' => 'btn btn-primary btn-xs create-customer m-1',
                                                            ]);
                                                        }
                                                    },
                                                    'hidden-input-customer-partner-info' => function ($url, $model) {
                                                        return Html::input('hidden', 'customer_partner_info[]', json_encode($model));
                                                    },
                                                    'hidden-input-customer-info' => function ($url, $model) {
                                                        $customer = CustomerTable::getRecordByPartnerInfoFromCache(Yii::$app->getModule('affiliate')->params['partner_id']['dashboard-myauris'], $model['id']);
                                                        if ($customer) {
                                                            return Html::input('hidden', 'customer_info[]', json_encode($customer));
                                                        }
                                                    }
                                                ],
                                                'headerOptions' => [
                                                    'width' => 250,
                                                ],
                                            ],
                                            [
                                                'class' => 'yii\grid\ActionColumn',
                                                'header' => Yii::t('backend', 'Related Record'),
                                                'template' => '{list-coupon} {list-note} {list-feedback}',
                                                'buttons' => [
                                                    'list-coupon' => function ($url, $model) {
                                                        if (!Utils::isReleaseObject('Coupon')) return '';

                                                        $record = CustomerTable::getRecordByPartnerInfoFromCache(Yii::$app->getModule('affiliate')->params['partner_id']['dashboard-myauris'], $model['id']);
                                                        if ($record) {
                                                            $count = Coupon::countByCustomer($record['id']);

                                                            $bage = $count ? '<span class="badge badge-light ml-1">' . $count . '</span>' : '';

                                                            return Html::a('<i class="icon dripicons-ticket"></i> ' . $bage, Url::toRoute(['/affiliate/coupon', 'CouponSearch[customer_id]' => $record['id']]), [
                                                                'title' => Yii::t('backend', 'List Tickets'),
                                                                'alia-label' => Yii::t('backend', 'List Tickets'),
                                                                'data-pjax' => 0,
                                                                'class' => 'btn btn-info btn-xs list-relate-record m-1',
                                                                'data-related-id' => $record['id'],
                                                                'data-related-field' => 'customer_id',
                                                                'data-model' => 'Coupon',
                                                                'target' => '_blank'
                                                            ]);
                                                        }

                                                        return '';
                                                    },
                                                    'list-note' => function ($url, $model) {
                                                        $record = CustomerTable::getRecordByPartnerInfoFromCache(Yii::$app->getModule('affiliate')->params['partner_id']['dashboard-myauris'], $model['id']);

                                                        if ($record) {
                                                            $count = Note::countByCustomer($record['id']);

                                                            $bage = $count ? '<span class="badge badge-light ml-1">' . $count . '</span>' : '';

                                                            return Html::a('<i class="icon dripicons-to-do"></i>' . $bage, Url::toRoute(['/affiliate/note', 'NoteSearch[customer_id]' => $record['id']]), [
                                                                'title' => Yii::t('backend', 'List Notes'),
                                                                'alia-label' => Yii::t('backend', 'List Notes'),
                                                                'data-pjax' => 0,
                                                                'class' => 'btn btn-success btn-xs list-relate-record m-1',
                                                                'data-related-id' => $record['id'],
                                                                'data-related-field' => 'customer_id',
                                                                'data-model' => 'Note',
                                                                'target' => '_blank'
                                                            ]);
                                                        }

                                                        return '';
                                                    },
                                                    'list-feedback' => function ($url, $model) {
                                                        $record = CustomerTable::getRecordByPartnerInfoFromCache(Yii::$app->getModule('affiliate')->params['partner_id']['dashboard-myauris'], $model['id']);

                                                        if ($record) {
                                                            $count = \modava\affiliate\models\Feedback::countByCustomer($record['id']);

                                                            $bage = $count ? '<span class="badge badge-light ml-1">' . $count . '</span>' : '';

                                                            return Html::a('<span class="material-icons" style="font-size: 12px">feedback</span>' . $bage, Url::toRoute(['/affiliate/feedback', 'FeedbackSearch[customer_id]' => $record['id']]), [
                                                                'title' => Yii::t('backend', 'List Feedback'),
                                                                'alia-label' => Yii::t('backend', 'List Feedback'),
                                                                'data-pjax' => 0,
                                                                'class' => 'btn btn-success btn-xs list-relate-record m-1',
                                                                'data-related-id' => $record['id'],
                                                                'data-related-field' => 'customer_id',
                                                                'data-model' => 'Feedback',
                                                                'target' => '_blank'
                                                            ]);
                                                        }

                                                        return '';
                                                    },
                                                ],
                                                'headerOptions' => [
                                                    'width' => 150,
                                                ],
                                            ],
                                            [
                                                'label' => Yii::t('backend', 'Customer Infomation'),
                                                'format' => 'raw',
                                                'headerOptions' => [
                                                    'class' => 'header-300'
                                                ],
                                                'value' => function ($model) {
                                                    return AffiliateDisplayHelper::getCustomerInformation($model);
                                                }
                                            ],
                                            [
                                                'label' => Yii::t('backend', 'Images Before/After'),
                                                'format' => 'raw',
                                                'headerOptions' => [
                                                    'class' => 'header-300'
                                                ],
                                                'value' => function ($model) {
                                                    return AffiliateDisplayHelper::getImages($model);
                                                }
                                            ],
                                            [
                                                'label' => Yii::t('backend', 'Order Infomation'),
                                                'format' => 'raw',
                                                'headerOptions' => ['class' => 'header-300'],
                                                'contentOptions' => ['class' => 'header-400'],
                                                'value' => function ($model) use ($dropdowns) {
                                                    return AffiliateDisplayHelper::getOrderInformation($model, $dropdowns['thao_tac']);
                                                }
                                            ],
                                            [
                                                'label' => Yii::t('backend', 'Thông tin lịch điều trị'),
                                                'format' => 'raw',
                                                'headerOptions' => ['class' => 'header-300'],
                                                'contentOptions' => ['class' => 'header-400 pr'],
                                                'value' => function ($model) use ($dropdowns) {
                                                    return AffiliateDisplayHelper::getTreatmentSchedule($model, $dropdowns['thao_tac']);
                                                }
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
    </div>
<?= JsUtils::widget() ?>
<?php
$script = <<< JS
$('.create-coupon').on('click', function() {
    let customerInfo = JSON.parse($(this).closest('td').find('[name="customer_info[]"]').val());
    openCreateModal({model: 'Coupon', 
        'Coupon[customer_id]' : customerInfo.id,
    });
});

$('.create-call-note').on('click', function() {
    let customerInfo = JSON.parse($(this).closest('td').find('[name="customer_info[]"]').val());
    openCreateModal({model: 'Note', 
        'Note[customer_id]' : customerInfo.id,
    });
});

$('.create-feedback').on('click', function() {
    let customerInfo = JSON.parse($(this).closest('td').find('[name="customer_info[]"]').val());
    openCreateModal({model: 'Feedback', 
        'Feedback[customer_id]' : customerInfo.id,
    });
});

$('.create-customer').on('click', function() {
    let customerInfo = JSON.parse($(this).closest('td').find('[name="customer_partner_info[]"]').val());
    console.log('birday:', customerInfo.birthday);
    debugger;
    
    openCreateModal({
        model: 'Customer',
        'Customer[full_name]' : customerInfo.full_name,
        'Customer[phone]' : customerInfo.phone,
        'Customer[face_customer]' : customerInfo.face_customer,
        'Customer[partner_id]' : $myAuris->primaryKey,
        'Customer[partner_customer_id]' : customerInfo.id,
        'Customer[birthday]' : customerInfo.birthday ? moment(customerInfo.birthday, 'DD-MM-YYYY').format('YYYY-MM-DD') : '',
        'Customer[sex]' : customerInfo.sex,
        'Customer[province_id]' : customerInfo.province,
        'Customer[district_id]' : customerInfo.district,
        'Customer[address]' : customerInfo.address,
        'Customer[date_accept_do_service]' : customerInfo.customer_come_date ? moment.unix(customerInfo.customer_come_date).format("YYYY-MM-DD") : '', 
        'Customer[date_checkin]' : customerInfo.time_lichhen ? moment.unix(customerInfo.time_lichhen).format("YYYY-MM-DD") : ''
    });
});

$('body').on('post-object-created', function() {
    window.location.reload();
});

$('.customer-img-container').lightGallery();

$('.search-btn').on('click', function(e) {
    e.preventDefault();
    let href = $(this).attr('href');
    if ($('[name="ClinicSearch[last_dieu_tri]"]').is(':checked')) href += "&ClinicSearch[last_dieu_tri]=on";
    
    window.location.href = href;
})
JS;
$this->registerJs($script, \yii\web\View::POS_END);