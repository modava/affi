<?php

use yii\db\Migration;

/**
 * Class m200916_030433_create_table_affiliate_sms_log
 */
class m200916_030433_create_table_affiliate_sms_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%affiliate_sms_log}}', [
            'id' => $this->primaryKey(),
            'message' => $this->text()->notNull(),
            'to_number' => $this->string(20)->notNull(),
            'customer_id' => $this->integer(11)->notNull(),
            'status' => $this->string(50),
            'response_log' => $this->text(),
            'request_log' => $this->text(),
            'created_at' => $this->integer(11)->notNull()->comment('Thời gian gửi'),
            'created_by' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->addForeignKey('fk-affiliate_sms_log-user_created-by_user-id', 'affiliate_sms_log', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_af_sms_log_customer_id_af_customer_id', 'affiliate_sms_log', 'customer_id', 'affiliate_customer', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200916_030433_create_table_affiliate_sms_log cannot be reverted.\n";

        return false;
    }
}
