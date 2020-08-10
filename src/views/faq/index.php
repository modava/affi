<?php

use kartik\select2\Select2;
use modava\affiliate\AffiliateModule;
use modava\affiliate\widgets\NavbarWidgets;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\widgets\ToastrWidget;
use yii\web\JsExpression;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel modava\affiliate\models\search\FaqSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = AffiliateModule::t('affiliate', 'Faqs');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $searchModel->toastr_key . '-index']) ?>
<style>a:hover{text-decoration: underline!important;}</style>

<div class="container-fluid px-xxl-25 px-xl-10">
    <?= NavbarWidgets::widget(); ?>

    <!-- Title -->
    <div class="hk-pg-header">
        <h4 class="hk-pg-title"><span class="pg-title-icon"><span
                        class="ion ion-md-apps"></span></span><?= Html::encode($this->title) ?>
        </h4>
        <a class="btn btn-outline-light" href="<?= \yii\helpers\Url::to(['create']); ?>"
           title="<?= AffiliateModule::t('affiliate', 'Create'); ?>">
            <i class="fa fa-plus"></i> <?= AffiliateModule::t('affiliate', 'Create A Question'); ?></a>
    </div>

    <!-- Row -->
    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel])?>

    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <div class="row">
                    <div class="col-sm">
                        <div class="table-wrap">
                            <div class="dataTables_wrapper dt-bootstrap4 table-responsive px-4">
                                <?= ListView::widget([
                                    'dataProvider' => $dataProvider,
                                    'summaryOptions' => [
                                            'class' => 'mb-4'
                                    ],
                                    'itemView' => function ($model, $key, $index, $widget) {
                                        return "
                                         <div class='mb-4'>
                                            <h5 class='py-1'><a href='" . \yii\helpers\Url::toRoute(['view', 'id' => $model->primaryKey]) . "'>{$model->title}</a></h5>
                                            <p>{$model->short_content}</p>
                                            <p>{$model->faqCategory->title}</p>
                                        </div>
                                        
                                    ";
                                    },
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
                                ]);?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <?php Pjax::end(); ?>
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