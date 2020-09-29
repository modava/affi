<?php

use backend\widgets\ToastrWidget;
use common\grid\MyGridView;
use modava\affiliate\helpers\Utils;
use modava\affiliate\widgets\DropdownWidget;
use modava\affiliate\widgets\JsUtils;
use modava\affiliate\widgets\NavbarWidgets;
use yii\helpers\Html;
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
            <a class="btn btn-outline-light btn-sm" href="<?= \yii\helpers\Url::to(['create']); ?>"
               title="<?= Yii::t('backend', 'Create'); ?>">
                <i class="fa fa-plus"></i> <?= Yii::t('backend', 'Create'); ?></a>
        </div>

        <!-- Row -->
        <div class="row">
            <div class="col-xl-12">
                <?= $this->render('_search', ['model' => $searchModel]); ?>

                <section class="hk-sec-wrapper index">

                    <?php Pjax::begin(['id' => 'dt-pjax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
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
                                            'data-minus' => '{"0":95,"1":".hk-navbar","2":".nav-tabs","3":".hk-pg-header","4":".hk-footer-wrap","5":"#customer-search"}'
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
                                                'class' => 'yii\grid\ActionColumn',
                                                'header' => Yii::t('backend', 'Actions'),
                                                'template' => DropdownWidget::widget([
                                                        'title' => Yii::t('backend', 'Hành động'),
                                                        'dropdowns' => [
                                                            '{create-coupon}',
                                                            '{create-call-note}',
                                                            '{hidden-input-customer-info}',
                                                            '{create-feedback}',
                                                            '{update}',
                                                            '{delete}',
                                                        ],
                                                        'isCustomItem' => true,
                                                        'options' => [
                                                            'class' => 'btn-success btn-sm fs-12'
                                                        ]
                                                    ]) . DropdownWidget::widget([
                                                        'title' => Yii::t('backend', 'DS liên quan'),
                                                        'dropdowns' => [
                                                            '{list-coupon}',
                                                            '{list-note}',
                                                            '{list-feedback}',
                                                        ],
                                                        'isCustomItem' => true,
                                                        'options' => [
                                                            'class' => 'btn-success btn-sm fs-12'
                                                        ]
                                                    ]),
                                                'buttons' => [
                                                    'update' => function ($url, $model) {
                                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>' . ' ' . Yii::t('backend', 'Cập nhật'), $url, [
                                                            'title' => Yii::t('backend', 'Update'),
                                                            'alia-label' => Yii::t('backend', 'Update'),
                                                            'data-pjax' => 0,
                                                            'class' => 'btn btn-info btn-xs m-1',
                                                        ]);
                                                    },
                                                    'delete' => function ($url, $model) {
                                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>' . ' ' . Yii::t('backend', 'Xóa'), 'javascript:;', [
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

                                                        return Html::a('<i class="icon dripicons-ticket"></i>' . ' ' . Yii::t('backend', 'Tạo Coupon'), 'javascript:;', [
                                                            'title' => Yii::t('backend', 'Create Coupon'),
                                                            'alia-label' => Yii::t('backend', 'Create Coupon'),
                                                            'data-pjax' => 0,
                                                            'data-partner' => 'myaris',
                                                            'class' => 'btn btn-info btn-xs create-coupon m-1'
                                                        ]);
                                                    },
                                                    'create-call-note' => function ($url, $model) {
                                                        return Html::a('<i class="icon dripicons-to-do"></i>' . ' ' . Yii::t('backend', 'Tạo Note cuộc gọi'), 'javascript:;', [
                                                            'title' => Yii::t('backend', 'Create Call Note'),
                                                            'alia-label' => Yii::t('backend', 'Create Call Note'),
                                                            'data-pjax' => 0,
                                                            'data-partner' => 'myaris',
                                                            'class' => 'btn btn-success btn-xs create-call-note m-1'
                                                        ]);
                                                    },
                                                    'create-feedback' => function ($url, $model) {
                                                        return Html::a('<span class="material-icons" style="font-size: 12px">feedback</span>' . ' ' . Yii::t('backend', 'Tạo FeedBack'), 'javascript:;', [
                                                            'title' => Yii::t('backend', 'Create Feedback'),
                                                            'alia-label' => Yii::t('backend', 'Create Feedback'),
                                                            'data-pjax' => 0,
                                                            'data-partner' => 'myaris',
                                                            'class' => 'btn btn-success btn-xs create-feedback m-1'
                                                        ]);

                                                    },
                                                    'hidden-input-customer-info' => function ($url, $model) {
                                                        return Html::input('hidden', 'customer_info[]', json_encode($model->getAttributes()));
                                                    },
                                                    'list-coupon' => function ($url, $model) {
                                                        if (!Utils::isReleaseObject('Coupon')) return '';

                                                        $count = count($model->coupons);

                                                        $bage = $count ? '<span class="badge badge-light ml-1">' . $count . '</span>' : '';

                                                        return Html::a('<i class="icon dripicons-ticket"></i> ' . ' ' . Yii::t('backend', 'Coupon') . $bage, Url::toRoute(['/affiliate/coupon', 'CouponSearch[customer_id]' => $model->primaryKey]), [
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

                                                        return Html::a('<i class="icon dripicons-to-do"></i>' . ' ' . Yii::t('backend', 'Note') . $bage, Url::toRoute(['/affiliate/note', 'NoteSearch[customer_id]' => $model->primaryKey]), [
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
                                                    'list-feedback' => function ($url, $model) {
                                                        $count = count($model->feedbacks);

                                                        $bage = $count ? '<span class="badge badge-light ml-1">' . $count . '</span>' : '';

                                                        return Html::a('<span class="material-icons" style="font-size: 12px">feedback</span>' . ' ' . Yii::t('backend', 'FeedBack') . $bage, Url::toRoute(['/affiliate/feedback', 'FeedbackSearch[customer_id]' => $model->primaryKey]), [
                                                            'title' => Yii::t('backend', 'List Feedback'),
                                                            'alia-label' => Yii::t('backend', 'List Feedback'),
                                                            'data-pjax' => 0,
                                                            'class' => 'btn btn-success btn-xs list-relate-record m-1',
                                                            'data-related-id' => $model->primaryKey,
                                                            'data-related-field' => 'customer_id',
                                                            'data-model' => 'Feedback',
                                                            'target' => '_blank'
                                                        ]);
                                                    }
                                                ],
                                                'headerOptions' => [
                                                    'class' => 'header-100',
                                                ],
                                            ],
                                            [
                                                'attribute' => 'full_name',
                                                'format' => 'raw',
                                                'headerOptions' => [
                                                    'class' => 'header-200',
                                                ],
                                                'value' => function ($model) {
                                                    $content = "<strong>Họ và tên: </strong>" . Html::a($model->full_name, ['view', 'id' => $model->id], [
                                                            'title' => $model->full_name,
                                                            'data-pjax' => 0,
                                                        ]) . '<br/>';

                                                    $gender = $model->sex ? Yii::$app->getModule('affiliate')->params['sex'][$model->sex] : '';
                                                    $content .= "<strong>Giới tính: </strong>" . $gender . '<br/>';
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
                                                'attribute' => 'partner_id',
                                                'headerOptions' => [
                                                    'class' => 'header-100',
                                                ],
                                                'format' => 'raw',
                                                'value' => function ($model) {
                                                    return Html::a($model->partner->title, Url::toRoute(['/affiliate/partner/view', 'id' => $model->partner_id]));
                                                }
                                            ],
                                            [
                                                'attribute' => 'birthday',
                                                'format' => 'raw',
                                                'headerOptions' => [
                                                    'class' => 'header-200',
                                                ],
                                                'value' => function ($model) {
                                                    $content = "<strong>Ngày sinh: </strong>" . ($model->birthday ? Yii::$app->formatter->asDate($model->birthday) : '') . "<br/>";
                                                    $content .= "<strong>CMND/CTCD: </strong>" . $model->id_card_number . "<br/>";
                                                    $content .= "<strong>Phương thức chuyển khoản HH: </strong>" . ($model->payment_type ? Yii::$app->getModule('affiliate')->params['customer_payment_type'][$model->payment_type] : '') . "<br/>";
                                                    return $content;
                                                }
                                            ],
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
                                                'format' => 'date',
                                                'headerOptions' => [
                                                    'class' => 'header-100',
                                                ],
                                            ],
                                        ],
                                        'rowOptions' => function ($model) {
                                            if ($model->isUnsatisfied()) {
                                                return ['class' => 'bg-danger'];
                                            }
                                        },
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
        'Feedback[customer_id]' : customerInfo.id
    });
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);