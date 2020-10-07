<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\widgets\ToastrWidget;
use modava\affiliate\widgets\NavbarWidgets;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\Payment */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $model->toastr_key . '-view']) ?>
    <div class="container-fluid px-xxl-25 px-xl-10">
        <?= NavbarWidgets::widget(); ?>

        <!-- Title -->
        <div class="hk-pg-header">
            <h4 class="hk-pg-title"><span class="pg-title-icon"><span
                            class="ion ion-md-apps"></span></span><?= Yii::t('backend', 'Chi tiết'); ?>
                : <?= Html::encode($this->title) ?>
            </h4>
            <p>
                <?php if (Yii::$app->user->can('admin') || Yii::$app->user->can('develop')): ?>
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
                <?php endif; ?>
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
                            'title',
                            [
                                'attribute' => 'customer_id',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::a($model->customer->full_name, Url::toRoute(['/affiliate/customer/view', 'id' => $model->customer_id]));
                                }
                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model->status === \modava\affiliate\models\Payment::STATUS_DRAFT) $class = 'badge-light';
                                    else $class = 'badge-success';
                                    $tag = Html::tag('span', Yii::$app->getModule('affiliate')->params['payment_status'][$model->status], ['class' => "badge p-2 {$class}"]);
                                    return Html::tag('h5', $tag);
                                }
                            ],
                            'amount:currency',
                            [
                                'label' => Yii::t('backend', 'Hình Ảnh'),
                                'format' => 'raw',
                                'headerOptions' => [
                                    'class' => 'header-300'
                                ],
                                'value' => function ($model) {
                                    return $model->getDisplayImages();
                                }
                            ],
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
        </div>
    </div>

