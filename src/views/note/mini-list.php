<?php
/* @var $notes */

use \modava\affiliate\AffiliateModule;
use modava\affiliate\helpers\AffiliateDisplayHelper;

?>

<?php foreach ($notes as $note): ?>
    <tr data-record-id="<?= $note->primaryKey ?>">
        <td class="w-sm" title="<?= $note->title ?>"><?= $note->title ?></td>
        <td class="w-sm" title="<?= $note->customer->full_name ?>"><?= $note->customer->full_name ?></td>
        <td class="w-sm"
            title="<?= $note->customer->phone ?>"><?= AffiliateDisplayHelper::getPhone($note->customer) ?></td>
        <td class="w-md"><?= Yii::$app->formatter->asDatetime($note->call_time) ?></td>
        <td class="w-md"><?= Yii::$app->formatter->asDatetime($note->recall_time) ?></td>
        <td class="w-sm">
            <a href="#" data-trigger="focus" data-toggle="popover"
               title="<?= Yii::t('backend', 'Note') ?>"
               data-content="<?= $note->description ?>"><?= Yii::t('backend', 'Chi tiết') ?></a>
        </td>
        <td class="w-sm text-center"><input type="checkbox"></td>
    </tr>
<?php endforeach; ?>

