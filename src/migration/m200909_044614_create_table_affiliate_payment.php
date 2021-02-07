<?php

use yii\db\Migration;

/**
 * Class m200909_044614_create_table_affiliate_payment
 */
class m200909_044614_create_table_affiliate_payment extends Migration
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

        $this->createTable('{{%affiliate_payment}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull()->comment('Chi cho việc gì'),
            'slug' => $this->string(255)->notNull()->unique(),
            'customer_id' => $this->integer(11)->notNull()->comment('Khách hàng'),
            'amount' => $this->decimal(11)->defaultValue(0)->notNull()->comment('Số tiền chi'),
            'status' => $this->smallInteger(1)->defaultValue(1)->notNull()->comment('Tình trạng chi'),
            'description' => $this->text()->null()->comment('Mô tả'),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->createIndex('idx-slug', 'affiliate_payment', 'slug', true);
        $this->addForeignKey('fk-affiliate_payment-user_created-by_user-id', 'affiliate_payment', 'created_by', 'user', 'id');
        $this->addForeignKey('fk-affiliate_payment-user_updated-by_user-id', 'affiliate_payment', 'updated_by', 'user', 'id');
        $this->addForeignKey('fk-affiliate_payment-customer_id-af_customer__id', 'affiliate_payment', 'customer_id', 'affiliate_customer', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200909_044614_create_table_affiliate_payment cannot be reverted.\n";

        return false;
    }
}
