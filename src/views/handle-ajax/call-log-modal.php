<?php

use modava\affiliate\AffiliateModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $data */
/* @var $phone */
?>

<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title"
                id="createCouponModalLabel"><?= Yii::t('backend', 'Lịch sử cuộc gọi của') . ' ' . $phone ?></h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" style="height: 70vh; overflow-y: scroll">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>
                        <?= Yii::t('backend', 'Hướng') ?>
                    </th>
                    <th>
                        <?= Yii::t('backend', 'Từ số') ?>
                    </th>
                    <th>
                        <?= Yii::t('backend', 'Đến số') ?>
                    </th>
                    <th>
                        <?= Yii::t('backend', 'Ghi âm') ?>
                    </th>
                    <th>
                        <?= Yii::t('backend', 'Ngày gọi') ?>
                    </th>
                </tr>
                </thead>
                <tbody>

                <?php if ($data): foreach ($data as $record): ?>
                    <tr>
                        <td>
                            <?php
                            if ($record['direction'] == 1) {
                                echo Yii::t('backend', 'Gọi vào');
                            } else if ($record['direction'] == 3) {
                                echo Yii::t('backend', 'Gọi ra');
                            } else echo '-' ?>
                        </td>
                        <td><?= $record['from_number'] ?></td>
                        <td><?= $record['to_number'] ?></td>
                        <td>
                            <?php if ($record['recording_url']) : ?>
                                <audio class="call-log-audio" controls="controls" autobuffer="autobuffer">
                                    <source src="<?= $record['recording_path'] ?>">
                                    Your browser does not support the audio element.
                                </audio>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?=Yii::$app->formatter->asDatetime($record['time_started'])?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5">
                            <div class="alert alert-success">
                                <?= Yii::t('backend', 'Không có cuộc gọi nào') ?>
                            </div>
                        </td>
                    </tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>