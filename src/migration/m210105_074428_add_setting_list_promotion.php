<?php

use yii\db\Migration;

/**
 * Class m210105_074428_add_setting_list_promotion
 */
class m210105_074428_add_setting_list_promotion extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `website_key_value`(`title`, `key`, `value`, `status`, `language`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES ('Danh sách giá trị giảm giá tối đa (viết liền cách nhau bằng dấu phẩy)', 'LIST_PROMO_PERCENT', '10,15,20', 1, '', 1609830839, 1609830839, 1, 1);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210105_074428_add_setting_list_promotion cannot be reverted.\n";

        return false;
    }
}
