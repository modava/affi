<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\widgets\ToastrWidget;
use modava\affiliate\widgets\NavbarWidgets;
use modava\affiliate\AffiliateModule;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\Order */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => AffiliateModule::t('affiliate', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $model->toastr_key . '-view']) ?>
<div class="container-fluid px-xxl-25 px-xl-10">
    <?= NavbarWidgets::widget(); ?>

    <!-- Title -->
    <div class="hk-pg-header">
        <h4 class="hk-pg-title"><span class="pg-title-icon"><span
                        class="ion ion-md-apps"></span></span><?= Html::encode($this->title) ?>
        </h4>
    </div>
    <!-- /Title -->

    <!-- Row -->
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
						'title',
						[
						    'attribute' => 'coupon_id',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::a($model->coupon->coupon_code, Url::toRoute(['coupon/view', 'id' => $model->coupon_id]), [
                                        'target' => '_blank',
                                        'data-pjax' => 0
                                ]);
                            }
                        ],
                        'partner_order_code',
                        'partner_customer_id',
                        [
                            'attribute' => 'status',
                            'value' => function ($model) {
                                if ($model->status === null) return null;

                                return Yii::$app->getModule('affiliate')->params['order_status'][$model->status];
                            },
                        ],
                        [
                            'attribute' => 'date_create',
                            'format' => 'datetime'
                        ],
                        [
                            'attribute' => 'pre_total',
                            'format' => 'currency',
                        ],
                        [
                            'attribute' => 'discount',
                            'format' => 'currency',
                        ],
                        [
                            'attribute' => 'final_total',
                            'format' => 'currency',
                        ],
						'description:raw',
						'created_at:datetime',
						'updated_at:datetime',
                        [
                            'attribute' => 'userCreated.userProfile.fullname',
                            'label' => AffiliateModule::t('affiliate', 'Created By')
                        ],
                        [
                            'attribute' => 'userUpdated.userProfile.fullname',
                            'label' => AffiliateModule::t('affiliate', 'Updated By')
                        ],
                    ],
                ]) ?>
            </section>
        </div>
    </div>
</div>
