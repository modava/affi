<?php

use modava\affiliate\AffiliateModule;
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

$this->title = AffiliateModule::t('affiliate', 'Customers');
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
           title="<?= AffiliateModule::t('affiliate', 'Create'); ?>">
            <i class="fa fa-plus"></i> <?= AffiliateModule::t('affiliate', 'Create'); ?></a>
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
                                            'format' => 'raw',
                                            'value' => function ($model) {
                                                return Html::a($model->full_name, ['view', 'id' => $model->id], [
                                                    'title' => $model->full_name,
                                                    'data-pjax' => 0,
                                                ]);
                                            }
                                        ],
										'phone',
										'email:email',
                                        'face_customer',
                                        [
                                            'attribute' => 'partner_id',
                                            'format' => 'raw',
                                            'value' => function ($model) {
                                                return $model->partner_id ? Html::a($model->partner->title, Url::toRoute(['/affiliate/partner/view', 'id' => $model->partner_id])) : '';
                                            }
                                        ],
                                        //'description:ntext',
                                        [
                                            'attribute' => 'created_by',
                                            'value' => 'userCreated.userProfile.fullname',
                                            'headerOptions' => [
                                                'width' => 150,
                                            ],
                                        ],
                                        [
                                            'attribute' => 'created_at',
                                            'format' => 'date',
                                            'headerOptions' => [
                                                'width' => 150,
                                            ],
                                        ],
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'header' => AffiliateModule::t('affiliate', 'Related Record'),
                                            'template' => '{list-coupon} {list-note}',
                                            'buttons' => [
                                                'list-coupon' => function ($url, $model) {
                                                    return Html::button('<i class="icon dripicons-ticket"></i>', [
                                                        'title' => AffiliateModule::t('affiliate', 'List Tickets'),
                                                        'alia-label' => AffiliateModule::t('affiliate', 'List Tickets'),
                                                        'data-pjax' => 0,
                                                        'class' => 'btn btn-info btn-xs list-relate-record',
                                                        'data-related-id' => $model->primaryKey,
                                                        'data-related-field' => 'customer_id',
                                                        'data-model' => 'Coupon',
                                                        'onclick' => 'getListRelatedRecords(this)'
                                                    ]);
                                                },
                                                'list-note' => function ($url, $model) {
                                                    return Html::button('<i class="icon dripicons-to-do"></i>', [
                                                        'title' => AffiliateModule::t('affiliate', 'List Notes'),
                                                        'alia-label' => AffiliateModule::t('affiliate', 'List Notes'),
                                                        'data-pjax' => 0,
                                                        'class' => 'btn btn-success btn-xs list-relate-record',
                                                        'data-related-id' => $model->primaryKey,
                                                        'data-related-field' => 'customer_id',
                                                        'data-model' => 'Note',
                                                        'onclick' => 'getListRelatedRecords(this)'
                                                    ]);
                                                },
                                            ],
                                            'headerOptions' => [
                                                'width' => 150,
                                            ],
                                        ],
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'header' => AffiliateModule::t('affiliate', 'Actions'),
                                            'template' => '{create-coupon} {create-call-note} {hidden-input-customer-info} {update} {delete}',
                                            'buttons' => [
                                                'update' => function ($url, $model) {
                                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                                        'title' => AffiliateModule::t('affiliate', 'Update'),
                                                        'alia-label' => AffiliateModule::t('affiliate', 'Update'),
                                                        'data-pjax' => 0,
                                                        'class' => 'btn btn-info btn-xs',
                                                    ]);
                                                },
                                                'delete' => function ($url, $model) {
                                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'javascript:;', [
                                                        'title' => AffiliateModule::t('affiliate', 'Delete'),
                                                        'class' => 'btn btn-danger btn-xs btn-del',
                                                        'data-title' => AffiliateModule::t('affiliate', 'Delete?'),
                                                        'data-pjax' => 0,
                                                        'data-url' => $url,
                                                        'btn-success-class' => 'success-delete',
                                                        'btn-cancel-class' => 'cancel-delete',
                                                        'data-placement' => 'top'
                                                    ]);
                                                },
                                                'create-coupon' => function ($url, $model) {
                                                    return Html::a('<i class="icon dripicons-ticket"></i>', 'javascript:;', [
                                                        'title' => AffiliateModule::t('affiliate', 'Create Coupon'),
                                                        'alia-label' => AffiliateModule::t('affiliate', 'Create Coupon'),
                                                        'data-pjax' => 0,
                                                        'data-partner' => 'myaris',
                                                        'class' => 'btn btn-info btn-xs create-coupon'
                                                    ]);
                                                },
                                                'create-call-note' => function ($url, $model) {
                                                    return Html::a('<i class="icon dripicons-to-do"></i>', 'javascript:;', [
                                                        'title' => AffiliateModule::t('affiliate', 'Create Call Note'),
                                                        'alia-label' => AffiliateModule::t('affiliate', 'Create Call Note'),
                                                        'data-pjax' => 0,
                                                        'data-partner' => 'myaris',
                                                        'class' => 'btn btn-success btn-xs create-call-note'
                                                    ]);
                                                },
                                                'hidden-input-customer-info' => function ($url, $model) {
                                                    return Html::input('hidden', 'customer_info[]', json_encode($model->getAttributes()));
                                                }
                                            ],
                                            'headerOptions' => [
                                                'width' => 150,
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