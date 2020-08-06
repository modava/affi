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

$this->title = AffiliateModule::t('affiliate', 'Customer');
$this->params['breadcrumbs'][] = $this->title;
$myAuris = PartnerSearch::getRecordBySlug('dashboard-myauris');
Yii::$app->controller->module->params['partner_id']['dashboard-myauris'] = $myAuris->primaryKey;

/* @var $listThaotac */
/* @var $dataProvider */
/* @var $payload */

?>

<?= ToastrWidget::widget(['key' => 'toastr-affiliate-list']) ?>

    <div class="container-fluid px-xxl-25 px-xl-10">
        <?= NavbarWidgets::widget(); ?>

        <!-- Row -->
        <div class="row">
            <div class="col-xl-12">
                <form action="<?=Url::toRoute(['/affiliate/affiliate'])?>" method="get" width="100%">
                <div class="hk-sec-wrapper">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-lg-4">
                            <div class="form-group row">
                                <div class="col-4"><?=AffiliateModule::t('affiliate', 'Date Range')?>: </div>
                                <div class="col-8">
                                    <input type="text" name="ClinicSearch[appointment_time]" class="form-control" placeholder="<?=AffiliateModule::t('affiliate', 'Date')?>" value="<?=$payload['ClinicSearch[appointment_time]']?>"></div>
                            </div>
                        </div>
<!--                        <div class="col-md-4 col-sm-6 col-lg-4">-->
<!--                            <div class="form-group row">-->
<!--                                <div class="col-4">-->
<!--                                    --><?//=AffiliateModule::t('affiliate', 'Action')?><!--:-->
<!--                                </div>-->
<!--                                <div class="col-6">-->
<!--                                    <select name="ClinicSearch[thao_tac]" id="" class="form-control">-->
<!--                                        <option value="">--><?//=AffiliateModule::t('affiliate', 'Select an action...')?><!--</option>-->
<!--                                        --><?php //foreach($listThaotac as $id => $name): ?>
<!--                                            <option value="--><?//=$id?><!--" --><?php //if ($payload['ClinicSearch[thao_tac]'] === (string) $id) echo 'selected';?><!-- >--><?//=$name?><!--</option>-->
<!--                                        --><?php //endforeach;?>
<!--                                    </select>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                        <div class="col-12">
                            <button type="submit" class="btn-success btn"><?=AffiliateModule::t('affiliate', 'Search')?></button>
                        </div>
                    </div>
                </div>
                </form>
            </div>

            <div class="col-xl-12">
                <section class="hk-sec-wrapper">
                    <div class="row">
                        <div class="col-sm">
                            <div class="table-wrap">
                                <div class="dataTables_wrapper dt-bootstrap4">
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
                                            'firstPageLabel' => AffiliateModule::t('affiliate', 'First'),
                                            'lastPageLabel' => AffiliateModule::t('affiliate', 'Last'),
                                            'prevPageLabel' => AffiliateModule::t('affiliate', 'Previous'),
                                            'nextPageLabel' => AffiliateModule::t('affiliate', 'Next'),
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
                                                'attribute' => 'full_name',
                                                'label' => AffiliateModule::t('affiliate', 'Full Name'),
                                                'format' => 'raw',
                                                'value' => function ($model) {
                                                    $message = AffiliateModule::t('affiliate', 'Converted');
                                                    $tick = '';

                                                    if (CustomerTable::getRecordByPartnerInfoFromCache(Yii::$app->controller->module->params['partner_id']['dashboard-myauris'], $model['id'])) {
                                                        $tick = Html::tag('span', '', [
                                                            'title' => $message,
                                                            'alia-label' => $message,
                                                            'class' => 'glyphicon glyphicon-ok text-success m-1',
                                                        ]);
                                                    }

                                                    return $model['full_name'] . $tick;
                                                }
                                            ],
                                            [
                                                'attribute' => 'sex',
                                                'label' => AffiliateModule::t('affiliate', 'Sex'),
                                                'value' => function ($model) {
                                                    return Yii::$app->controller->module->params['sex'][$model['sex']];
                                                }
                                            ],
                                            [
                                                'attribute' => 'birthday',
                                                'label' => AffiliateModule::t('affiliate', 'Birthday'),
                                            ],
                                            [
                                                'label' => AffiliateModule::t('affiliate', 'Phone'),
                                                'format' => 'raw',
                                                'value' => function ($model) {
                                                    $content = '';
                                                    if (class_exists('modava\voip24h\CallCenter')) $content .= Html::a('<i class="fa fa-phone"></i>', 'javascript: void(0)', [
                                                        'class' => 'btn btn-xs btn-success call-to',
                                                        'title' => 'Gọi',
                                                        'data-uri' => $model['phone']
                                                    ]);
                                                    $content .= Html::a('<i class="fa fa-paste"></i>', 'javascript: void(0)', [
                                                        'class' => 'btn btn-xs btn-info copy ml-1',
                                                        'title' => 'Copy'
                                                    ]);
                                                    return $content;
                                                }
                                            ],

                                            [
                                                'class' => 'yii\grid\ActionColumn',
                                                'header' => AffiliateModule::t('affiliate', 'Related Record'),
                                                'template' => '{list-coupon} {list-note}',
                                                'buttons' => [
                                                    'list-coupon' => function ($url, $model) {
                                                        if (!Utils::isReleaseObject('Coupon')) return '';

                                                        $record = CustomerTable::getRecordByPartnerInfoFromCache(Yii::$app->controller->module->params['partner_id']['dashboard-myauris'], $model['id']);
                                                        if ($record) {
                                                            $count = Coupon::countByCustomer($record['id']);

                                                            $bage = $count ? '<span class="badge badge-light ml-1">' . $count . '</span>' : '';

                                                            return Html::a('<i class="icon dripicons-ticket"></i> ' . $bage , Url::toRoute(['/affiliate/coupon', 'CouponSearch[customer_id]' => $record['id']]),[
                                                                'title' => AffiliateModule::t('affiliate', 'List Tickets'),
                                                                'alia-label' => AffiliateModule::t('affiliate', 'List Tickets'),
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
                                                        $record = CustomerTable::getRecordByPartnerInfoFromCache(Yii::$app->controller->module->params['partner_id']['dashboard-myauris'], $model['id']);

                                                        if ($record) {
                                                            $count = Note::countByCustomer($record['id']);

                                                            $bage = $count ? '<span class="badge badge-light ml-1">' . $count . '</span>' : '';

                                                            return Html::a('<i class="icon dripicons-to-do"></i>' . $bage, Url::toRoute(['/affiliate/note', 'NoteSearch[customer_id]' => $record['id']]),[
                                                                'title' => AffiliateModule::t('affiliate', 'List Notes'),
                                                                'alia-label' => AffiliateModule::t('affiliate', 'List Notes'),
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
                                                ],
                                                'headerOptions' => [
                                                    'width' => 150,
                                                ],
                                            ],
                                            [
                                                'class' => 'yii\grid\ActionColumn',
                                                'header' => AffiliateModule::t('affiliate', 'Actions'),
                                                'template' => '{create-customer} {create-coupon} {create-call-note} {hidden-input-customer-partner-info} {hidden-input-customer-info}',
                                                'buttons' => [
                                                    'create-coupon' => function ($url, $model) {
                                                        if (!Utils::isReleaseObject('Coupon')) return '';

                                                        if (CustomerTable::getRecordByPartnerInfoFromCache(Yii::$app->controller->module->params['partner_id']['dashboard-myauris'], $model['id'])) {
                                                            return Html::a('<i class="icon dripicons-ticket"></i>', 'javascript:;', [
                                                                'title' => AffiliateModule::t('affiliate', 'Create Coupon'),
                                                                'alia-label' => AffiliateModule::t('affiliate', 'Create Coupon'),
                                                                'data-pjax' => 0,
                                                                'data-partner' => 'myaris',
                                                                'class' => 'btn btn-info btn-xs create-coupon m-1'
                                                            ]);
                                                        }

                                                        return '';

                                                    },
                                                    'create-call-note' => function ($url, $model) {
                                                        if (CustomerTable::getRecordByPartnerInfoFromCache(Yii::$app->controller->module->params['partner_id']['dashboard-myauris'], $model['id'])) {
                                                            return Html::a('<i class="icon dripicons-to-do"></i>', 'javascript:;', [
                                                                'title' => AffiliateModule::t('affiliate', 'Create Call Note'),
                                                                'alia-label' => AffiliateModule::t('affiliate', 'Create Call Note'),
                                                                'data-pjax' => 0,
                                                                'data-partner' => 'myaris',
                                                                'class' => 'btn btn-success btn-xs create-call-note m-1'
                                                            ]);
                                                        }

                                                        return '';

                                                    },
                                                    'create-customer' => function ($url, $model) {
                                                        $record = CustomerTable::getRecordByPartnerInfoFromCache(Yii::$app->controller->module->params['partner_id']['dashboard-myauris'], $model['id']);
                                                        if ($record) {
                                                            $message = AffiliateModule::t('affiliate', 'Detail');

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
                                                        }
                                                        else {
                                                            $message = AffiliateModule::t('affiliate', 'Convert');

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
                                                        $customer = CustomerTable::getRecordByPartnerInfoFromCache(Yii::$app->controller->module->params['partner_id']['dashboard-myauris'], $model['id']);
                                                        if ($customer) {
                                                            return Html::input('hidden', 'customer_info[]', json_encode($customer));
                                                        }
                                                    }
                                                ],
                                                'headerOptions' => [
                                                    'width' => 250,
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
    </div>
<?=JsUtils::widget()?>
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

$('[name="ClinicSearch[appointment_time]"]').daterangepicker({
    opens: 'right',
    cancelClass: "btn-secondary",
    showDropdowns: true,
    autoApply: true,
    locale:{
        format:'DD-MM-YYYY',
    }
}, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);