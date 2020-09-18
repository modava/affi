<?php
/* @var $notes */

use \modava\affiliate\AffiliateModule;
use modava\affiliate\helpers\AffiliateDisplayHelper;
use yii\helpers\Url;

$saveUrl = Url::toRoute(["/affiliate/handle-ajax/update-ajax"]);
?>

<?php if (!count($notes)) echo '<tr><td colspan="7">Không có dữ liệu được tìm thấy</td></tr>'?>

<?php foreach ($notes as $note): ?>
    <tr data-record-id="<?= $note->primaryKey ?>">
        <td class="w-sm" title="<?= $note->title ?>"><?= $note->title ?></td>
        <td class="w-sm" title="<?= $note->customer->full_name ?>"><?= $note->customer->full_name ?></td>
        <td class="w-sm"
            title="<?= $note->customer->phone ?>"><?= AffiliateDisplayHelper::getPhone($note->customer) ?></td>
        <td class="w-sm text-center"><input name="Note[is_recall]"
                                            type="checkbox" <?= $note->is_recall ? 'checked' : '' ?> ></td>
        <td class="w-md"><?= Yii::$app->formatter->asDatetime($note->call_time) ?></td>
        <td class="w-md"><?= Yii::$app->formatter->asDatetime($note->recall_time) ?></td>
        <td class="w-sm">
            <a href="#" data-trigger="focus" data-toggle="popover"
               title="<?= Yii::t('backend', 'Note') ?>"
               data-content="<?= $note->description ?>"><?= Yii::t('backend', 'Chi tiết') ?></a>
        </td>
    </tr>
<?php endforeach; ?>

<script>
    $('[name="Note[is_recall]"]').on('click', function () {
        let id = $(this).closest('tr').data('record-id');
        $.post('<?= $saveUrl ?>?id=' + id, {
            'Note[is_recall]': $(this).is(':checked') ? 1 : 0,
            'model': 'Note'
        }, function (response) {
            if (response && response.success === true) {
                $.toast({
                    heading: 'Thông báo',
                    text: 'Thành công',
                    position: 'top-right',
                    class: 'jq-toast-success',
                    hideAfter: 2000,
                    stack: 6,
                    showHideTransition: 'fade'
                });
            } else {
                $.toast({
                    heading: 'Thông báo',
                    text: response.message,
                    position: 'top-right',
                    class: 'jq-toast-danger',
                    hideAfter: 2000,
                    stack: 6,
                    showHideTransition: 'fade'
                });
            }
        });
    })


    $('.copy').on('click', function () {
        let text = $(this).data('copy');
        let dummy = document.createElement("input");
        document.body.appendChild(dummy);
        dummy.setAttribute("id", "dummy_id");
        document.getElementById("dummy_id").value = text;
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(dummy);
        $.toast({
            heading: 'Thông báo',
            text: 'Copy thành công',
            position: 'top-right',
            class: 'jq-toast-success',
            hideAfter: 2000,
            stack: 6,
            showHideTransition: 'fade'
        });
    })
</script>

