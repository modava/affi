<?php

use yii\db\Migration;

/**
 * Class m201211_022708_modify_affiliate_coupon_add_field
 */
class m201211_022708_modify_affiliate_coupon_add_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('affiliate_coupon', 'commission_for', $this->integer(11));
        $this->addForeignKey('fk_affiliate_coupon_commission_for_user_id', 'affiliate_coupon', 'commission_for', 'user', 'id');
        $this->execute('UPDATE affiliate_coupon SET commission_for = created_by');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201211_022708_modify_affiliate_coupon_add_field cannot be reverted.\n";

        return false;
    }

}
