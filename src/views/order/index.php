<?php

use backend\widgets\ToastrWidget;
use common\grid\MyGridView;
use modava\affiliate\widgets\JsUtils;
use modava\affiliate\widgets\NavbarWidgets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel modava\affiliate\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Orders');
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
            <!--            <a class="btn btn-outline-light" href="-->
            <? //= \yii\helpers\Url::to(['create']); ?><!--"-->
            <!--               title="--><? //= Yii::t('backend', 'Create'); ?><!--">-->
            <!--                <i class="fa fa-plus"></i> --><? //= Yii::t('backend', 'Create'); ?><!--</a>-->
        </div>

        <!-- Row -->
        <?php Pjax::begin(['id' => 'dt-pjax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
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
                                            Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_pageTo',
                                                [
                                                    'totalPage' => $totalPage,
                                                    'currentPage' => Yii::$app->request->get($dataProvider->getPagination()->pageParam)
                                                ]) .
                                            Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_pageSize')
                                            .
                                            '{pager}
                                        </div>
                                        ',
                                        'tableOptions' => [
                                            'id' => 'dataTable',
                                            'class' => 'dt-grid dt-widget pane-hScroll',
                                        ],
                                        'myOptions' => [
                                            'class' => 'dt-grid-content my-content pane-vScroll',
                                            'data-minus' => '{"0":105,"1":".hk-navbar","2":".nav-tabs","3":".hk-pg-header","4":".hk-footer-wrap"}'
                                        ],
                                        'summaryOptions' => [
                                            'class' => 'summary pull-right',
                                        ],
                                        'pager' => [
                                            'firstPageLabel' => Yii::t('backend', 'First'),
                                            'lastPageLabel' => Yii::t('backend', 'Last'),
                                            'prevPageLabel' => Yii::t('backend', 'Previous'),
                                            'nextPageLabel' => Yii::t('backend', 'Next'),
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
                                                ]
                                            ],
                                            [
                                                'attribute' => 'coupon_id',
                                                'format' => 'raw',
                                                'value' => function ($model) {
                                                    return Html::a($model->coupon->coupon_code, Url::toRoute(['coupon/view', 'id' => $model->coupon_id]), [
                                                        'target' => '_blank',
                                                        'data-pjax' => 0
                                                    ]);
                                                },
                                                'headerOptions' => [
                                                    'class' => 'header-200',
                                                ]
                                            ],
                                            'partner_order_code',
                                            'partner_customer_id',
                                            [
                                                'attribute' => 'status',
                                                'value' => function ($model) {
                                                    if ($model->status === null) return null;

                                                    return Yii::$app->getModule('affiliate')->params['order_status'][$model->status];
                                                },
                                                'contentOptions' => [
                                                    'class' => 'header-200'
                                                ]
                                            ],
                                            [
                                                'attribute' => 'date_create',
                                                'format' => 'datetime'
                                            ],
                                            [
                                                'attribute' => 'pre_total',
                                                'format' => 'currency',
                                                'contentOptions' => [
                                                    'class' => 'text-right',
                                                ]
                                            ],
                                            [
                                                'attribute' => 'discount',
                                                'format' => 'currency',
                                                'contentOptions' => [
                                                    'class' => 'text-right',
                                                ],
                                            ],
                                            [
                                                'attribute' => 'commision_for_coupon_owner',
                                                'format' => 'currency',
                                                'contentOptions' => [
                                                    'class' => 'text-right',
                                                ],
                                            ],
                                            [
                                                'attribute' => 'other_discount',
                                                'format' => 'currency',
                                                'contentOptions' => [
                                                    'class' => 'text-right',
                                                ],
                                            ],
                                            [
                                                'attribute' => 'final_total',
                                                'format' => 'currency',
                                                'contentOptions' => [
                                                    'class' => 'text-right',
                                                ]
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
                                                'format' => 'datetime',
                                                'headerOptions' => [
                                                    'width' => 150,
                                                ],
                                            ],
                                            [
                                                'class' => 'yii\grid\ActionColumn',
                                                'header' => Yii::t('backend', 'Actions'),
                                                'template' => '',
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
                </section>
            </div>
        </div>
        <?php Pjax::end(); ?>
    </div>

<?= JsUtils::widget() ?>
<?php
$urlChangePageSize = \yii\helpers\Url::toRoute(['perpage']);
$script = <<< JS
$('body').on('click', '.success-delete', function(e){
    e.preventDefault();
    var url = $(this).attr('href') || null;
    if(url !== null){
        $.post(url);
    }
    return false;
});
var customPjax = new myGridView();
customPjax.init({
pjaxId: '#dt-pjax',
urlChangePageSize: '$urlChangePageSize',
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);