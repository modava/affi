<?php

use modava\affiliate\AffiliateModule;

/* @var $errorMessage */

?>

<div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="ErrorModalLabel"><?=AffiliateModule::t('affiliate', 'An Error Occur')?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <?=$errorMessage?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=AffiliateModule::t('affiliate', 'Close')?></button>
        </div>
    </div>
</div>