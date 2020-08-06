<?php

use modava\affiliate\helpers\Utils;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\widgets\ToastrWidget;
use modava\affiliate\widgets\NavbarWidgets;
use modava\affiliate\AffiliateModule;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\Customer */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => AffiliateModule::t('affiliate', 'Customers'), 'url' => ['index']];
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
        <p>
            <a class="btn btn-outline-light" href="<?= Url::to(['create']); ?>"
                title="<?= AffiliateModule::t('affiliate', 'Create'); ?>">
                <i class="fa fa-plus"></i> <?= AffiliateModule::t('affiliate', 'Create'); ?></a>
            <?= Html::a(AffiliateModule::t('affiliate', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(AffiliateModule::t('affiliate', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => AffiliateModule::t('affiliate', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    </div>
    <!-- /Title -->

    <!-- Row -->
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'label'=> AffiliateModule::t('affiliate', 'Related Record'),
                            'format'=>'raw',
                            'value'=> function ($model) {
                                $listButton = '';

                                if (Utils::isReleaseObject('Coupon')) {
                                    $count = count($model->coupons);

                                    $bage = $count ? '<span class="badge badge-light ml-1">' . $count . '</span>' : '';

                                    $listButton .= Html::a('<i class="icon dripicons-ticket"></i> ' . $bage , Url::toRoute(['/affiliate/coupon', 'CouponSearch[customer_id]' => $model->primaryKey]),[
                                        'title' => AffiliateModule::t('affiliate', 'List Tickets'),
                                        'alia-label' => AffiliateModule::t('affiliate', 'List Tickets'),
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

                                    $listButton .= Html::a('<i class="icon dripicons-to-do"></i>' . $bage, Url::toRoute(['/affiliate/note', 'NoteSearch[customer_id]' => $model->primaryKey]),[
                                        'title' => AffiliateModule::t('affiliate', 'List Notes'),
                                        'alia-label' => AffiliateModule::t('affiliate', 'List Notes'),
                                        'data-pjax' => 0,
                                        'class' => 'btn btn-success btn-xs list-relate-record m-1',
                                        'data-related-id' => $model->primaryKey,
                                        'data-related-field' => 'customer_id',
                                        'data-model' => 'Note',
                                        'target' => '_blank'
                                    ]);
                                }

                                return $listButton;
                            },
                        ],
						'id',
						'slug',
						'full_name',
						'phone',
						'email:email',
                        'face_customer',
                        'birthday:date',
                        [
                            'attribute' => 'sex',
                            'value' => function ($model) {
                                return Yii::$app->controller->module->params['sex'][$model->sex];
                            }
                        ],
                        'date_accept_do_service:date',
                        'date_checkin:date',
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
						'description:ntext',
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
