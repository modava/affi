<?php

use modava\affiliate\AffiliateModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $modelName */
/* @var $filePath */
/* @var $model */
/* @var $dataProvider */
/* @var $searchModel */
?>

<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="createCouponModalLabel"><?=AffiliateModule::t('affiliate', 'List'). ' ' . AffiliateModule::t('affiliate', $modelName)?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" style="height: 70vh; overflow-y: scroll">
            <?=\Yii::$app->view->renderFile($filePath, ['searchModel' => $searchModel, 'dataProvider' => $dataProvider,]);?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>