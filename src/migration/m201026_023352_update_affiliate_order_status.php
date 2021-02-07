<?php

use yii\db\Migration;

/**
 * Class m201026_023352_update_affiliate_order_status
 */
class m201026_023352_update_affiliate_order_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('UPDATE `affiliate_order` SET STATUS = 4 WHERE `status` = 3 AND commision_for_coupon_owner = 0');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201026_023352_update_affiliate_order_status cannot be reverted.\n";

        return false;
    }
}
