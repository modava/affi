<?php

use \modava\affiliate\models\Order;

/* @var $orders */

if (!count($orders)) echo '<tr><td colspan="7">Không có dữ liệu được tìm thấy</td></tr>';
?>
<?php foreach ($orders as $order): ?>
    <tr data-record-id="<?= $order->primaryKey ?>">
        <td class="w-sm-30" title="<?= $order->title ?>"><?= $order->title ?></td>
        <td class="w-sm-30"><?= \yii\helpers\Html::a($order->coupon->coupon_code, \yii\helpers\Url::toRoute(['coupon/view', 'id' => $order->coupon_id]), [
                'target' => '_blank',
                'data-pjax' => 0
            ]) ?></td>
        <td class="w-sm-30">
            <?php $class = 'badge-light';
            switch ($order->status) {
                case Order::CHUA_HOAN_THANH:
                    $class = 'badge-light';
                    break;
                case Order::HOAN_THANH:
                    $class = 'badge-primary';
                    break;
                case Order::HUY:
                    $class = 'badge-danger';
                    break;
                case Order::KE_TOAN_DUYET:
                    $class = 'badge-info';
                    break;
                case Order::DA_THANH_TOAN:
                    $class = 'badge-success';
                    break;
            } ?>
            <?= \yii\helpers\Html::tag('span', Yii::$app->getModule('affiliate')->params['order_status'][$order->status], ['class' => 'font-11 badge ' . $class]) ?>
        </td>
        <td class="w-md-30"><?= Yii::$app->formatter->asCurrency($order->pre_total) ?></td>
    </tr>
<?php endforeach; ?>