<?php

use yii\db\Migration;

/**
 * Class m201207_024756_add_setting_default_coupon_expired_date
 */
class m201207_024756_add_setting_default_coupon_expired_date extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `website_key_value`(`title`, `key`, `value`, `status`, `language`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES ('Thời gian hết hạn coupon mặc định', 'COUPON_DATE_EXPIRED', '+3 months', 1, '', 1607309085, 1607309216, 1, 1);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201207_024756_add_setting_default_coupon_expired_date cannot be reverted.\n";

        return false;
    }
}
