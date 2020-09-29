<?php

use backend\widgets\ToastrWidget;
use modava\affiliate\helpers\Utils;
use modava\affiliate\models\Coupon;
use modava\affiliate\models\search\PartnerSearch;
use modava\affiliate\widgets\NavbarWidgets;
use modava\chart\MiniList;
use modava\charts\BarChart;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\Customer */
/* @var $orderDataProvider */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$dataProvider = new ActiveDataProvider([
    'query' => Coupon::find()->where('customer_id = :customer_id', [':customer_id' => $model->primaryKey]),
    'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
]);
?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $model->toastr_key . '-view']) ?>
    <div class="container-fluid px-xxl-25 px-xl-10">
        <?= NavbarWidgets::widget(); ?>

        <!-- Title -->
        <div class="hk-pg-header">
            <h4 class="hk-pg-title"><span class="pg-title-icon"><span
                            class="ion ion-md-apps"></span></span><?= Html::encode($this->title) ?>
            </h4>
            <p>
                <?php if ($model->partner_id === PartnerSearch::getRecordBySlug('dashboard-myauris')->id) : ?>
                    <button class="btn btn-primary js-more-info btn-sm" data-customer-id="<?= $model->partner_customer_id ?>"><?= Yii::t('backend', 'More Information') ?></button>
                <?php endif; ?>
                <a class="btn btn-outline-light btn-sm" href="<?= Url::to(['create']); ?>"
                   title="<?= Yii::t('backend', 'Create'); ?>">
                    <i class="fa fa-plus"></i> <?= Yii::t('backend', 'Create'); ?></a>
                <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
                <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-sm',
                    'data' => [
                        'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        </div>
        <!-- /Title -->

        <!-- Row -->
        <div class="row">
            <div class="col-xl-12">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#menu1"><?= Yii::t('backend', 'Tổng quan') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#detail"><?= Yii::t('backend', 'Chi tiết') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#list-order"><?= Yii::t('backend', 'DS Đơn sử dụng Coupon') ?></a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="menu1">
                        <div class="row">
                            <div class="col-6 my-3">
                                <?= BarChart::widget([
                                    'id' => 'tong_hoa_hong_theo_kh',
                                    'url_get_data' => Url::toRoute(['/affiliate/customer/total-commission', 'id' => $model->primaryKey]),
                                    'height' => '400px',
                                    'title' => Yii::t('backend', 'Tổng hoa hồng theo tháng'),
                                    'options' => [
                                        'color' => ['#69c982']
                                    ]
                                ]) ?>
                            </div>
                            <div class="col-6 my-3">
                                <?= MiniList::widget([
                                    'title' => Yii::t('backend', 'Danh sách SMS đã gửi'),
                                    'columns' => [
                                        'Nội dung',
                                        'Người gửi',
                                        'Tình trạng',
                                        'Ngày gửi',
                                    ],
                                    'url_get_data' => Url::toRoute(["/affiliate/sms-log/get-sms-by-customer", 'customer_id' => $model->primaryKey]),
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="detail">
                        <section class="hk-sec-wrapper">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    [
                                        'label' => Yii::t('backend', 'Related Record'),
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $listButton = '';

                                            if (Utils::isReleaseObject('Coupon')) {
                                                $count = count($model->coupons);
                                                $bage = $count ? '<span class="badge badge-light ml-1">' . $count . '</span>' : '';
                                                $listButton .= Html::a('<i class="icon dripicons-ticket"></i> ' . $bage, Url::toRoute(['/affiliate/coupon', 'CouponSearch[customer_id]' => $model->primaryKey]), [
                                                    'title' => Yii::t('backend', 'List Tickets'),
                                                    'alia-label' => Yii::t('backend', 'List Tickets'),
                                                    'data-pjax' => 0,
                                                    'class' => 'btn btn-info btn-xs list-relate-record m-1',
                                                    'data-related-id' => $model->primaryKey,
                                                    'data-related-field' => 'customer_id',
                                                    'data-model' => 'Coupon',
                                                    'target' => '_blank'
                                                ]);
                                            }

                                            if (Utils::isReleaseObject('Note')) {
                                                $count = count($model->notes);
                                                $bage = $count ? '<span class="badge badge-light ml-1">' . $count . '</span>' : '';
                                                $listButton .= Html::a('<i class="icon dripicons-to-do"></i>' . $bage, Url::toRoute(['/affiliate/note', 'NoteSearch[customer_id]' => $model->primaryKey]), [
                                                    'title' => Yii::t('backend', 'List Notes'),
                                                    'alia-label' => Yii::t('backend', 'List Notes'),
                                                    'data-pjax' => 0,
                                                    'class' => 'btn btn-success btn-xs list-relate-record m-1',
                                                    'data-related-id' => $model->primaryKey,
                                                    'data-related-field' => 'customer_id',
                                                    'data-model' => 'Note',
                                                    'target' => '_blank'
                                                ]);
                                            }

                                            $count = count($model->feedbacks);
                                            $bage = $count ? '<span class="badge badge-light ml-1">' . $count . '</span>' : '';
                                            $listButton .= Html::a('<span class="material-icons" style="font-size: 12px">feedback</span>' . $bage, Url::toRoute(['/affiliate/feedback', 'FeedbackSearch[customer_id]' => $model->primaryKey]), [
                                                'title' => Yii::t('backend', 'List Feedback'),
                                                'alia-label' => Yii::t('backend', 'List Feedback'),
                                                'data-pjax' => 0,
                                                'class' => 'btn btn-success btn-xs list-relate-record m-1',
                                                'data-related-id' => $model->primaryKey,
                                                'data-related-field' => 'customer_id',
                                                'data-model' => 'Feedback',
                                                'target' => '_blank'
                                            ]);

                                            return $listButton;
                                        },
                                    ],
                                    'full_name',
                                    'phone',
                                    'email:email',
                                    'face_customer',
                                    'birthday:date',
                                    [
                                        'attribute' => 'sex',
                                        'value' => function ($model) {
                                            if (!$model->sex) return '';
                                            return Yii::$app->getModule('affiliate')->params['sex'][$model->sex];
                                        }
                                    ],
                                    'id_card_number',
                                    [
                                        'attribute' => 'payment_type',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->payment_type ? Yii::$app->getModule('affiliate')->params['customer_payment_type'][$model->payment_type] : '';
                                        }
                                    ],
                                    'total_commission:currency',
                                    'total_commission_paid:currency',
                                    'total_commission_remain:currency',
                                    'bank_name',
                                    'bank_branch',
                                    'bank_customer_id',
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
                                    [
                                        'attribute' => 'partner_id',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->partner_id ? Html::a($model->partner->title, Url::toRoute(['/affiliate/partner/view', 'id' => $model->partner_id])) : '';
                                        }
                                    ],
                                    'address:raw',
                                    [
                                        'attribute' => 'status',
                                        'headerOptions' => [
                                            'class' => 'header-100',
                                        ],
                                        'value' => function ($model) {
                                            return Yii::$app->getModule('affiliate')->params['customer_status'][$model->status];
                                        }
                                    ],
                                    'date_accept_do_service:date',
                                    'date_checkin:date',
                                    'description:raw',
                                    'created_at:datetime',
                                    'updated_at:datetime',
                                    [
                                        'attribute' => 'userCreated.userProfile.fullname',
                                        'label' => Yii::t('backend', 'Created By')
                                    ],
                                    [
                                        'attribute' => 'userUpdated.userProfile.fullname',
                                        'label' => Yii::t('backend', 'Updated By')
                                    ],
                                ],
                            ]) ?>
                        </section>
                    </div>
                    <div class="tab-pane fade" id="list-order">
                        <section class="hk-sec-wrapper table-responsive">
                            <?php Pjax::begin(['id' => 'list-order-use-coupon', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]); ?>
                            <?= GridView::widget([
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
                                'dataProvider' => $orderDataProvider,
                                'columns' => [
                                    [
                                        'attribute' => 'title',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return Html::a($model->title, ['order/view', 'id' => $model->id], [
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
                                                'data-pjax' => 0
                                            ]);
                                        },
                                        'headerOptions' => [
                                            'class' => 'header-200',
                                        ]
                                    ],
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
                                    'partner_order_code',
                                    'partner_customer_id',
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
                                ],
                            ]) ?>
                            <?php Pjax::end(); ?>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php

$controllerURL = Url::toRoute(["/affiliate/handle-ajax"]);

$script = <<< JS
function showMoreInfoCustomer(customerId) {
        let modalHTML = `<div class="modal fade ModalContainer" tabindex="-1" role="dialog" aria-labelledby="ModalContainer" aria-hidden="true"></div>`;

        if ($('.ModalContainer').length) $('.ModalContainer').remove();

        $('body').append(modalHTML);
        
    $.get('$controllerURL/get-customer-more-info', {customerId, model: 'Customer'}, function(data, status, xhr) {
        if (status === 'success') {
            $('.ModalContainer').html(data);
            $('.ModalContainer').modal();
            $('.customer-img-container').lightGallery();
        }
    });
}

$('.js-more-info').on('click', function() {
    showMoreInfoCustomer($(this).data('customer-id'));
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);
