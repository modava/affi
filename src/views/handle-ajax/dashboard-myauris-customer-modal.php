<?php

use modava\affiliate\AffiliateModule;
use modava\affiliate\helpers\AffiliateDisplayHelper;

/* @var $model */
/* @var $listThaotac */
?>

<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="createCouponModalLabel"><?=Yii::t('backend', 'More Information')?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" style="height: 60vh; overflow-y: scroll">
            <div class="modal-img-container mb-4">
                <div class="d-flex justify-content-center">
                    <p class="mx-4" style="width: 100px; text-align: center"><?=Yii::t('backend', 'Before')?></p>
                    <p class="mx-4" style="width: 100px; text-align: center"><?=Yii::t('backend', 'After')?></p>
                </div>
                <?=AffiliateDisplayHelper::getImages($model, [ 'container_class' => 'd-flex justify-content-center', 'img_class' => 'mx-4' ])?>
            </div>

            <?php foreach ($model['don_hang'] as $donHang): $content = ''; ?>
            <div class="hk-sec-wrapper p-4">
                <p class="px-2 text-center"><strong><?=Yii::t('backend', 'Order Infomation')?></strong></p>
                <strong>Mã HĐ: <?=$donHang['order_code']?></strong> <i>( <?=date('d-m-Y', $donHang['ngay_tao']) ?>)</i><br/>

                <?php foreach ($donHang['chi_tiet'] as $chiTietDonHang):?>
                    <?=$chiTietDonHang['dich_vu']?> | <?=$chiTietDonHang['san_pham']?> : <strong><?=$chiTietDonHang['so_luong']?></strong><br/>
                <?php endforeach;?>

                <div class='d-flex justify-content-around mt-4'>
                <?php
                foreach ($donHang['lich_dieu_tri'] as $lichDieuTriInfo) {
                    $content1 = '';
                    $arrThaoTac = [];

                    foreach ($lichDieuTriInfo['thao_tac'] as $thaotac) {
                    $arrThaoTac[] = $listThaotac[$thaotac];
                    }

                    $content1 .= "<p class='p-2 text-center'><strong>Lịch điều trị</strong></p>";
                    $content1 .= '<strong>Thac tác: </strong>'. implode(', ', $arrThaoTac) . '<br>';
                    $content1 .= '<strong>Ekip:</strong> '. $lichDieuTriInfo['ekip'] . '<br>';
                    $content1 .= '<strong>Trợ thủ:</strong> '. implode(', ', $lichDieuTriInfo['tro_thu']) . '<br>';
                    $content1 .= '<strong>Ngày điều trị: </strong>'. date('d-m-Y H:i', $lichDieuTriInfo['time_dieu_tri']) . '<br>';

                    $content .= "<div class='hk-sec-wrapper header-300 mx-2 mb-0 px-3 py-2'>{$content1}</div>";
                }

                echo "{$content}";
                ?>
                </div>
            </div>
            <?php endforeach;?>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>