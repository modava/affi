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
            <h5 class="modal-title"
                id="createCouponModalLabel"><?= AffiliateModule::t('affiliate', 'Lịch sử cuộc gọi của') . ' ' . $phone ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" style="height: 70vh; overflow-y: scroll">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>
                        <?= AffiliateModule::t('affiliate', 'Hướng') ?>
                    </th>
                    <th>
                        <?= AffiliateModule::t('affiliate', 'Từ số') ?>
                    </th>
                    <th>
                        <?= AffiliateModule::t('affiliate', 'Đến số') ?>
                    </th>
                    <th>
                        <?= AffiliateModule::t('affiliate', 'Thời gian') ?>
                    </th>
                    <th>
                        <?= AffiliateModule::t('affiliate', 'Ghi âm') ?>
                    </th>
                    <th>
                        <?= AffiliateModule::t('affiliate', 'Ngày gọi') ?>
                    </th>
                </tr>
                </thead>
                <tbody>

                <?php if ($data): foreach ($data as $record): ?>
                    <tr>
                        <td>
                            <?php
                            if ($record['direction'] == 1) {
                                echo AffiliateModule::t('affiliate', 'Gọi vào');
                            } else if ($record['direction'] == 3) {
                                echo AffiliateModule::t('affiliate', 'Gọi ra');
                            } else echo '-' ?>
                        </td>
                        <td><?= $record['from_number'] ?></td>
                        <td><?= $record['to_number'] ?></td>
                        <td><?php
                            $hours = floor($record['duration'] / 3600);
                            $mins = floor($record['duration'] / 60 % 60);
                            $secs = floor($record['duration'] % 60);
                            echo sprintf('%02d:%02d:%02d', $hours, $mins, $secs)  ?></td>
                        <td>
                            <?php if ($record['recording_url']) : ?>
                                <audio controls="controls" autobuffer="autobuffer">
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
                                <?= AffiliateModule::t('affiliate', 'Không có cuộc gọi nào') ?>
                            </div>
                        </td>
                    </tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>