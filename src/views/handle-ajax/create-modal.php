<?php

use modava\affiliate\AffiliateModule;

/* @var $modelName */
/* @var $formPath */
/* @var $model */
?>

<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="createCouponModalLabel"><?=$modelName ? AffiliateModule::t('affiliate', 'Create'). ' ' . AffiliateModule::t('affiliate', $modelName) : AffiliateModule::t('affiliate', 'An Error Occur')?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" style="height: 60vh; overflow-y: scroll">
            <?php if (!$modelName): ?>
                <?=AffiliateModule::t('affiliate', 'An Error Occur')?>
            <?php else: ?>
                <?=\Yii::$app->view->renderFile($formPath, ['model' => $model]);?>
            <?php endif ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>