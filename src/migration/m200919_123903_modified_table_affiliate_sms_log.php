<?php

use yii\db\Migration;

/**
 * Class m200919_123903_modified_table_affiliate_sms_log
 */
class m200919_123903_modified_table_affiliate_sms_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('affiliate_sms_log', 'customer_id', $this->integer(11)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200919_123903_modified_table_affiliate_sms_log cannot be reverted.\n";

        return false;
    }
}
