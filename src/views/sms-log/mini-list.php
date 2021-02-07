<?php
/* @var $models */

use \modava\affiliate\AffiliateModule;
use modava\affiliate\helpers\AffiliateDisplayHelper;
use yii\helpers\Url;

$saveUrl = Url::toRoute(["/affiliate/handle-ajax/update-ajax"]);
?>

<?php foreach ($models as $model): ?>
    <tr data-record-id="<?= $model->primaryKey ?>">
        <td class="w-sm" title="<?= $model->message ?>" style="max-width: 300px;white-space: normal"><?= \yii\bootstrap\Html::a($model->message, Url::toRoute(['/affiliate/sms-log/view', 'id' => $model->id])) ?></td>
        <td class="w-sm"><?= \common\models\UserProfile::findOne($model->created_by)->fullname ?></td>
        <td class="w-sm"><?= Yii::$app->getModule('affiliate')->params['sms_log_status'][$model->status] ?></td>
        <td class="w-sm"><?= Yii::$app->formatter->asDatetime($model->created_at) ?></td>
    </tr>
<?php endforeach; ?>

