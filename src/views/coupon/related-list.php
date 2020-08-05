<?php

use modava\affiliate\AffiliateModule;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel modava\affiliate\models\search\CouponSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<?php Pjax::begin([
    'enablePushState' => false,
    'id' => 'coupon-related-list',
    'enableReplaceState'=>false
]); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
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
            'attribute' => 'title',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a($model->title, ['view', 'id' => $model->id], [
                    'title' => $model->title,
                    'data-pjax' => 0,
                ]);
            }
        ],
        'coupon_code',
        [
            'attribute' => 'customer_id',
            'format' => 'raw',
            'value' => function ($model) {
                return $model->customer_id ? Html::a($model->customer->full_name, Url::toRoute(['/affiliate/customer/view', 'id' => $model->customer_id])) : '';
            }
        ],
        'quantity',
        'quantity_used',
        [
            'attribute' => 'expired_date',
            'value' => function ($model) {
                return $model->expired_date
                    ? date('d-m-Y H:i', strtotime($model->expired_date))
                    : '';
            }
        ],
        [
            'attribute' => 'promotion_type',
            'value' => function ($model) {
                return AffiliateModule::t('affiliate', Yii::$app->controller->module->params["promotion_type"][$model->promotion_type]);
            }
        ],
        //'description:ntext',
        //'coupon_type_id',
        //'promotion_type',
        //'promotion_value',
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
    ],
]); ?>

<?php Pjax::end(); ?>