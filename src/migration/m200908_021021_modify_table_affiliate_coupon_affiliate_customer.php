<?php

use yii\db\Migration;

/**
 * Class m200908_021021_modify_table_affiliate_coupon_affiliate_customer
 */
class m200908_021021_modify_table_affiliate_coupon_affiliate_customer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Table Coupon
        $this->addColumn('affiliate_coupon', 'count_sms_sent', $this->smallInteger(1)->defaultValue(0)->comment('Số SMS đã gửi cho KH'));
        $this->addColumn('affiliate_coupon', 'max_discount', $this->decimal(11)->notNull()->comment('Giảm giá tối đa'));
        $this->addColumn('affiliate_coupon', 'min_discount', $this->decimal(11)->notNull()->comment('Giảm giá tối tối thiểu'));
        $this->addColumn('affiliate_coupon', 'commission_for_owner', $this->decimal(11)->notNull()->defaultValue(0)->comment('Hoa hồng dành cho chủ coupon'));

        // Table Customer
        $this->addColumn('affiliate_customer', 'total_commission', $this->decimal(15)->defaultValue(0)->comment('Số tiền hoa hồng của KH'));
        $this->addColumn('affiliate_customer', 'total_commission_paid', $this->decimal(15)->defaultValue(0)->comment('Số tiền hoa hồng đã trả cho KH'));
        $this->addColumn('affiliate_customer', 'total_commission_remain', $this->decimal(15)->defaultValue(0)->comment('Số tiền hoa hồng đã còn lại'));

        $this->execute("
            INSERT INTO `website_key_value`(`title`, `key`, `value`, `status`, `language`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES ('Max Promo Percent Value', 'MAX_PROMO_PERCENT_VALUE', '20', 1, '', 1599454066, 1599454066, 1, 1);
            INSERT INTO `website_key_value`(`title`, `key`, `value`, `status`, `language`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES ('Min Promo Percent Value', 'MIN_PROMO_PERCENT_VALUE', '8', 1, '', 1599557197, 1599557197, 1, 1);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200908_021021_modify_affiliate_coupon cannot be reverted.\n";

        return false;
    }
}
