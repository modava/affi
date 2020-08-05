<?php

use yii\db\Migration;

/**
 * Class m200731_040704_create_new_table_affiliate_customer
 */
class m200731_040704_create_new_table_affiliate_customer extends Migration
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

        $this->createTable('{{%affiliate_customer}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(255)->notNull()->unique(),
            'full_name' => $this->string(255)->notNull()->comment('Họ và tên Khách hàng'),
            'phone' => $this->string(15)->notNull()->unique()->comment('Số điện thoại - Không trùng'),
            'email' => $this->string(255)->null()->comment('Email khách hàng - không quan tâm trùng'),
            'face_customer' => $this->string(255)->null()->comment('Link facebook của KH'),
            'partner_id' => $this->integer(11)->notNull()->comment('Partner tích hợp affiliate'),
            'description' => $this->text()->null()->comment('Mô tả'),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'created_by' => $this->integer(11)->null()->comment('Người gọi'),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->createIndex('idx-slug', 'affiliate_customer', 'slug', true);
        $this->addForeignKey('fk-affiliate_customer-user_created-by_user-id', 'affiliate_customer', 'created_by', 'user', 'id');
        $this->addForeignKey('fk-affiliate_customer-user_updated-by_user-id', 'affiliate_customer', 'updated_by', 'user', 'id');
        $this->addForeignKey('fk_affiliate_customer_partner_id_partner_id', 'affiliate_customer', 'partner_id', 'affiliate_partner', 'id');
        $this->addForeignKey('fk-affiliate_coupon__customer_id-affiliate_customer__id', 'affiliate_coupon', 'customer_id', 'affiliate_customer', 'id');
        $this->addForeignKey('fk-affiliate_note__customer_id-affiliate_customer__id', 'affiliate_note', 'customer_id', 'affiliate_customer', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%affiliate_customer}}');
    }
}
