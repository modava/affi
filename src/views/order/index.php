<?php

use backend\widgets\ToastrWidget;
use common\grid\MyGridView;
use modava\affiliate\models\Order;
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
        <!-- Row -->
        <?php Pjax::begin(['id' => 'dt-pjax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
        <?php
        $gridColumns = [
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
                'footerOptions' => [
                    'colspan' => 3,
                    'class' => 'text-right font-weight-bold'
                ],
                'footer' => 'Tổng: ',
            ],
            [
                'attribute' => 'title',
                'label' => 'Thông tin đơn hàng',
                'format' => 'raw',
                'value' => function ($model) {
                    return
                        'Title: ' .
                        Html::a($model->title, ['view', 'id' => $model->id], [
                            'title' => $model->title,
                            'data-pjax' => 0,
                        ])
                        . '<br>Coupon: ' .
                        Html::a($model->coupon->coupon_code, Url::toRoute(['coupon/view', 'id' => $model->coupon_id]), [
                            'target' => '_blank',
                            'data-pjax' => 0
                        ]) . ' - ' . $model->coupon->commissionFor->userProfile->fullname . '<br> Mã đơn hàng partner: <strong>' . $model->partner_order_code . '</strong>'
                        . '<br> Mã khách hàng partner: <strong>' . $model->partner_customer_id . '</strong>';
                },
                'headerOptions' => [
                    'class' => 'header-300',
                ],
                'footerOptions' => [
                    'class' => 'd-none'
                ]
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    $content = '';
                    if ($model->status === null) return null;
                    $class = 'badge-light';
                    switch ($model->status) {
                        case Order::CHUA_HOAN_THANH:
                            $class = 'badge-light';
                            break;
                        case Order::HOAN_THANH:
                            $class = 'badge-primary';
                            break;
                        case Order::HUY:
                            $class = 'badge-danger';
                            break;
                        case Order::KE_TOAN_DUYET:
                            $class = 'badge-info';
                            break;
                        case Order::DA_THANH_TOAN:
                            $class = 'badge-success';
                            break;
                    }
                    $content = Html::tag('span', Yii::$app->getModule('affiliate')->params['order_status'][$model->status], ['class' => 'font-11 badge ' . $class]);
                    if ($model->status == Order::KE_TOAN_DUYET || $model->status == Order::DA_THANH_TOAN) {
                        $content .= '<p class="pt-1" ">Ngày duyệt: <strong>' . Yii::$app->formatter->asDatetime($model->date_approval_reception) . '</strong></p>';
                    }

                    return $content;
                },
                'headerOptions' => [
                    'class' => 'header-200 text-center',
                ],
                'contentOptions' => [
                    'class' => 'header-200 text-center',
                ],
                'footerOptions' => [
                    'class' => 'd-none'
                ]
            ],
            [
                'attribute' => 'created_by',
                'format' => 'raw',
                'label' => 'Chi tiết thanh toán',
                'value' => function ($model) {
                    $content = '';
                    $content .= 'Tổng tiền (trước triết khấu): <strong>' . Yii::$app->formatter->asCurrency($model->pre_total) . '</strong><br>';
                    $content .= 'Giảm giá: <strong>' . Yii::$app->formatter->asCurrency($model->discount) . '</strong><br>';
                    $content .= 'Chiết khấu cho chủ coupon: <strong>' . Yii::$app->formatter->asCurrency($model->commision_for_coupon_owner) . '</strong>';
                    return $content;
                },
                'headerOptions' => [
                    'class' => 'header-300 text-center',
                ],
                'footer' => '<strong>' . Yii::$app->formatter->asCurrency($total['sumPreTotal']) . '</strong>',
            ],
            [
                'attribute' => 'created_by',
                'format' => 'raw',
                'label' => 'Đối tác',
                'value' => function ($model) {
                    $content = '';
                    $content .= 'Người tạo: <strong>' . $model->userCreated->userProfile->fullname . '</strong><br>';
                    $content .= 'Ngày đơn hàng: <strong>' . Yii::$app->formatter->asDatetime($model->date_create) . '</strong><br>';
                    $content .= 'Ngày tạo: <strong>' . Yii::$app->formatter->asDatetime($model->created_at) . '</strong>';
                    return $content;
                },
                'headerOptions' => [
                    'class' => 'header-300 text-center',
                ],
                'footerOptions' => [
                    'colspan' => 2,
                ],
                'footer' => '',
            ],
        ];
        $gridColumnSearchs = [
            [
                'attribute' => 'partner_name',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->partner_name;
                },
            ],
            [
                'attribute' => 'partner_customer_code',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->partner_customer_code;
                },
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'label' => 'Đơn hàng',
                'value' => function ($model) {
                    return $model->title;
                },
            ],
            [
                'attribute' => 'title',
                'label' => 'Mã Affiliate',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->coupon->coupon_code;
                },
            ],
            [
                'attribute' => 'created_by',
                'format' => 'raw',
                'label' => 'Tổng tiền',
                'value' => function ($model) {
                    $content = '';
                    $content .= '<strong>' . $model->pre_total . '</strong><br>';
                    return $content;
                },
                'headerOptions' => [
                    'class' => 'header-300 text-center',
                ],
                'footer' => $total['sumPreTotal'],
            ],
            [
                'attribute' => 'final_total',
                'format' => 'raw',
                'label' => 'Doanh thu',
                'value' => function ($model) {
                    $content = '';
                    if ($model->status == Order::CHUA_HOAN_THANH) {
                        return $content;
                    }
                    $content .= '<strong>' . $model->final_total . '</strong>';
                    return $content;
                },
            ],
            [
                'attribute' => 'date_create',
                'format' => 'raw',
                'label' => 'Từ ngày',
                'value' => function ($model) {
                    /* @var $model Order */
                    $content = '';
                    $count = count($model->receipts);
                    foreach ($model->receipts as $k => $v) {
                        if ($count == 1 && $model->status == Order::CHUA_HOAN_THANH) {
                            $content = Yii::$app->formatter->asDate($v['receipt_date']);
                        } else if ($count >= 2) {
                            if ($model->status == Order::HOAN_THANH || $model->status == Order::DA_THANH_TOAN || $model->status == Order::KE_TOAN_DUYET) {
                                $content = Yii::$app->formatter->asDate($model->receipts[0]['receipt_date']);
                            }
                        }
                    }
                    return $content;
                },
            ],
            [
                'attribute' => 'date_approval_reception',
                'format' => 'raw',
                'label' => 'Đến ngày',
                'value' => function ($model) {
                    /* @var $model Order */
                    $content = '';
                    $count = count($model->receipts);
                    foreach ($model->receipts as $k => $v) {
                        if ($count == 1 && ($model->status == Order::HOAN_THANH || $model->status == Order::DA_THANH_TOAN || $model->status == Order::KE_TOAN_DUYET)) {
                            $content = Yii::$app->formatter->asDate($v['receipt_date']);
                        } else if ($count >= 2) {
                            if ($model->status == Order::HOAN_THANH || $model->status == Order::DA_THANH_TOAN || $model->status == Order::KE_TOAN_DUYET) {
                                $content = Yii::$app->formatter->asDate($model->receipts[$count - 1]['receipt_date']);
                            }
                        }
                    }
                    return $content;
                },
            ],
            [
                'attribute' => 'partner_receipted',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->status == Order::CHUA_HOAN_THANH)
                        return $model->partner_receipted ? $model->partner_receipted : '';
                    return '';
                },
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    $content = '';
                    if ($model->status === null) return null;
                    switch ($model->status) {
                        case Order::CHUA_HOAN_THANH:
                            $content = 'Chưa hoàn thành';
                            break;
                        case Order::HOAN_THANH:
                            $content = 'Hoàn thành';
                            break;
                        case Order::HUY:
                            $content = 'Huy';
                            break;
                        case Order::KE_TOAN_DUYET:
                            $content = 'Hoàn thành';
                            break;
                        case Order::DA_THANH_TOAN:
                            $content = 'Hoàn thành';
                            break;
                    }
                    return $content;
                },
            ],
        ];
        ?>
        <div class="row">
            <div class="col-xl-12">
                <?= $this->render('_search', [
                    'model' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'gridColumns' => $gridColumnSearchs,
                    'total' => $total,
                ]); ?>

                <section class="hk-sec-wrapper index">
                    <div class="row">
                        <div class="col-sm">
                            <div class="table-wrap">
                                <div class="dataTables_wrapper dt-bootstrap4 table-responsive">
                                    <?= MyGridView::widget([
                                        'dataProvider' => $dataProvider,
                                        'showFooter' => true,
                                        'placeFooterAfterBody' => true,
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
                                        'columns' => $gridColumns
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