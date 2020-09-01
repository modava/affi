<?php

use modava\affiliate\AffiliateModule;
use modava\affiliate\widgets\NavbarWidgets;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\widgets\ToastrWidget;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel modava\affiliate\models\search\NoteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Notes');
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

                <?php Pjax::begin(); ?>
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
                                            'attribute' => 'call_time',
                                            'value' => function ($model) {
                                                return $model->call_time
                                                    ? date('d-m-Y H:i', strtotime($model->call_time))
                                                    : '';
                                            },
                                            'headerOptions' => [
                                                'class' => 'header-200',
                                            ],
                                        ],
                                        [
                                            'attribute' => 'recall_time',
                                            'value' => function ($model) {
                                                return $model->recall_time
                                                    ? date('d-m-Y H:i', strtotime($model->recall_time))
                                                    : '';
                                            },
                                            'headerOptions' => [
                                                'class' => 'header-200',
                                            ],
                                        ],
                                        [
                                            'attribute' => 'description',
                                            'format' => 'raw',
                                            'headerOptions' => [
                                                'class' => 'header-400',
                                            ],
                                        ],
                                        [
                                            'attribute' => 'created_by',
                                            'value' => 'userCreated.userProfile.fullname',
                                            'headerOptions' => [
                                                'class' => 'header-300',
                                            ],
                                        ],
                                        [
                                            'attribute' => 'created_at',
                                            'format' => 'datetime',
                                            'headerOptions' => [
                                                'class' => 'header-300',
                                            ],
                                        ],
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'header' => Yii::t('backend', 'Actions'),
                                            'template' => '{update} {delete}',
                                            'buttons' => [
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
                <?php Pjax::end(); ?>
            </section>
        </div>
    </div>
</div>
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
JS;
$this->registerJs($script, \yii\web\View::POS_END);