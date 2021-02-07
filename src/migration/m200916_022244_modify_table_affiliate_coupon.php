<?php

use yii\db\Migration;

/**
 * Class m200916_022244_modify_table_affiliate_coupon
 */
class m200916_022244_modify_table_affiliate_coupon extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('affiliate_coupon', 'expired_date', $this->date()->null()->comment('Ngày hết hạn coupon'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200916_022244_modify_table_affiliate_coupon cannot be reverted.\n";

        return false;
    }
}
