<?php

use modava\affiliate\AffiliateModule;
use modava\affiliate\helpers\Utils;
use modava\affiliate\widgets\JsUtils;
use modava\affiliate\widgets\NavbarWidgets;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\widgets\ToastrWidget;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel modava\affiliate\models\search\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Customers');
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

            <?php //Pjax::begin(['enablePushState' => false, 'id' => 'customer-index']); ?>
                <div class="row">
                    <div class="col-sm">
                        <div class="table-wrap">
                            <div class="dataTables_wrapper dt-bootstrap4 table-responsive">
                                <?= GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'filterModel' => $searchModel,
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
                                            'template' => '{create-coupon} {create-call-note} {hidden-input-customer-info} {update} {delete}',
                                            'buttons' => [
                                                'update' => function ($url, $model) {
                                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                                        'title' => Yii::t('backend', 'Update'),
                                                        'alia-label' => Yii::t('backend', 'Update'),
                                                        'data-pjax' => 0,
                                                        'class' => 'btn btn-info btn-xs m-1',
                                                    ]);
                                                },
                                                'delete' => function ($url, $model) {
                                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'javascript:;', [
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
                                                'create-coupon' => function ($url, $model) {
                                                    if (!Utils::isReleaseObject('Coupon')) return '';

                                                    return Html::a('<i class="icon dripicons-ticket"></i>', 'javascript:;', [
                                                        'title' => Yii::t('backend', 'Create Coupon'),
                                                        'alia-label' => Yii::t('backend', 'Create Coupon'),
                                                        'data-pjax' => 0,
                                                        'data-partner' => 'myaris',
                                                        'class' => 'btn btn-info btn-xs create-coupon m-1'
                                                    ]);
                                                },
                                                'create-call-note' => function ($url, $model) {
                                                    return Html::a('<i class="icon dripicons-to-do"></i>', 'javascript:;', [
                                                        'title' => Yii::t('backend', 'Create Call Note'),
                                                        'alia-label' => Yii::t('backend', 'Create Call Note'),
                                                        'data-pjax' => 0,
                                                        'data-partner' => 'myaris',
                                                        'class' => 'btn btn-success btn-xs create-call-note m-1'
                                                    ]);
                                                },
                                                'hidden-input-customer-info' => function ($url, $model) {
                                                    return Html::input('hidden', 'customer_info[]', json_encode($model->getAttributes()));
                                                }
                                            ],
                                            'headerOptions' => [
                                                'class' => 'header-200',
                                            ],
                                        ],
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'header' => Yii::t('backend', 'Related Record'),
                                            'template' => '{list-coupon} {list-note}',
                                            'buttons' => [
                                                'list-coupon' => function ($url, $model) {
                                                    if (!Utils::isReleaseObject('Coupon')) return '';

                                                    $count = count($model->coupons);

                                                    $bage = $count ? '<span class="badge badge-light ml-1">' . $count . '</span>' : '';

                                                    return Html::a('<i class="icon dripicons-ticket"></i> ' . $bage , Url::toRoute(['/affiliate/coupon', 'CouponSearch[customer_id]' => $model->primaryKey]),[
                                                        'title' => Yii::t('backend', 'List Tickets'),
                                                        'alia-label' => Yii::t('backend', 'List Tickets'),
                                                        'data-pjax' => 0,
                                                        'class' => 'btn btn-info btn-xs list-relate-record m-1',
                                                        'data-related-id' => $model->primaryKey,
                                                        'data-related-field' => 'customer_id',
                                                        'data-model' => 'Coupon',
                                                        'target' => '_blank'
                                                    ]);
                                                },
                                                'list-note' => function ($url, $model) {
                                                    $count = count($model->notes);

                                                    $bage = $count ? '<span class="badge badge-light ml-1">' . $count . '</span>' : '';

                                                    return Html::a('<i class="icon dripicons-to-do"></i>' . $bage, Url::toRoute(['/affiliate/note', 'NoteSearch[customer_id]' => $model->primaryKey]),[
                                                        'title' => Yii::t('backend', 'List Notes'),
                                                        'alia-label' => Yii::t('backend', 'List Notes'),
                                                        'data-pjax' => 0,
                                                        'class' => 'btn btn-success btn-xs list-relate-record m-1',
                                                        'data-related-id' => $model->primaryKey,
                                                        'data-related-field' => 'customer_id',
                                                        'data-model' => 'Note',
                                                        'target' => '_blank'
                                                    ]);
                                                },
                                            ],
                                            'headerOptions' => [
                                                'width' => 150,
                                            ],
                                        ],
                                        [
                                            'attribute' => 'full_name',
                                            'format' => 'raw',
                                            'headerOptions' => [
                                                'class' => 'header-200',
                                            ],
                                            'value' => function ($model) {
                                                return Html::a($model->full_name, ['view', 'id' => $model->id], [
                                                    'title' => $model->full_name,
                                                    'data-pjax' => 0,
                                                ]);
                                            }
                                        ],
                                        [
                                            'attribute' => 'phone',
                                            'label' => Yii::t('backend', 'Phone'),
                                            'format' => 'raw',
                                            'headerOptions' => [
                                                'class' => 'header-100',
                                            ],
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
                                            'attribute' => 'email',
                                            'format' => 'email',
                                            'headerOptions' => [
                                                'class' => 'header-200',
                                            ],
                                        ],
                                        [
                                            'attribute' => 'face_customer',
                                            'headerOptions' => [
                                                'class' => 'header-200',
                                            ],
                                        ],
                                        [
                                            'attribute' => 'sex',
                                            'headerOptions' => [
                                                'class' => 'header-100',
                                            ],
                                            'value' => function ($model) {
                                                return Yii::$app->getModule('affiliate')->params['sex'][$model->sex];
                                            }
                                        ],
                                        [
                                            'attribute' => 'status',
                                            'headerOptions' => [
                                                'class' => 'header-100',
                                            ],
                                            'value' => function ($model) {
                                                return Yii::$app->getModule('affiliate')->params['customer_status'][$model->status];
                                            }
                                        ],
                                        [
                                            'attribute' => 'partner_id',
                                            'format' => 'raw',
                                            'value' => function ($model) {
                                                return $model->partner_id ? Html::a($model->partner->title, Url::toRoute(['/affiliate/partner/view', 'id' => $model->partner_id])) : '';
                                            },
                                            'headerOptions' => [
                                                'class' => 'header-100',
                                            ],
                                        ],
                                        [
                                            'attribute' => 'birthday',
                                            'format' => 'date',
                                            'headerOptions' => [
                                                'class' => 'header-100',
                                            ],
                                        ],
                                        [
                                            'attribute' => 'date_accept_do_service',
                                            'format' => 'date',
                                            'headerOptions' => [
                                                'class' => 'header-100',
                                            ],
                                        ],
                                        [
                                            'attribute' => 'date_checkin',
                                            'format' => 'date',
                                            'headerOptions' => [
                                                'class' => 'header-100',
                                            ],
                                        ],
                                        [
                                            'attribute' => 'country_id',
                                            'value' => function ($model) {
                                                return $model->country_id ? $model->country->CommonName : null;
                                            }
                                        ],
                                        [
                                            'attribute' => 'province_id',
                                            'value' => function ($model) {
                                                return $model->province_id ? $model->province->name : null;
                                            }
                                        ],
                                        [
                                            'attribute' => 'district_id',
                                            'value' => function ($model) {
                                                return $model->district_id ? $model->district->name : null;
                                            }
                                        ],
                                        [
                                            'attribute' => 'ward_id',
                                            'value' => function ($model) {
                                                return $model->ward_id ? $model->ward->name : null;
                                            }
                                        ],
                                        'bank_name',
                                        'bank_branch',
                                        'bank_customer_id',
                                        //'description:ntext',
                                        [
                                            'attribute' => 'created_by',
                                            'value' => 'userCreated.userProfile.fullname',
                                            'headerOptions' => [
                                                'class' => 'header-100',
                                            ],
                                        ],
                                        [
                                            'attribute' => 'created_at',
                                            'format' => 'datetime',
                                            'headerOptions' => [
                                                'class' => 'header-100',
                                            ],
                                        ],
                                    ],
                                ]); ?>
                                                            </div>
                        </div>
                    </div>
                </div>
                <?php //Pjax::end(); ?>
            </section>
        </div>
    </div>
</div>
<?=JsUtils::widget()?>
<?php
$script = <<< JS
$('body').on('click', '.success-delete', function(e){
    e.preventDefault();
    var url = $(this).attr('href') || null;
    if(url !== null){
        $.post(url);
    }
    return false;
});

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
JS;
$this->registerJs($script, \yii\web\View::POS_END);