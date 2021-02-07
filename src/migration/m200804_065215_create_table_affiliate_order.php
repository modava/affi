<?php

use yii\db\Migration;

/**
 * Class m200804_065215_create_table_affiliate_order
 */
class m200804_065215_create_table_affiliate_order extends Migration
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

        $this->createTable('{{%affiliate_order}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull()->unique(),
            'coupon_id' => $this->integer(11)->notNull()->comment('Mã coupon'),
            'pre_total' => $this->decimal(11)->notNull()->comment('Số tiền trên đơn hàng'),
            'discount' => $this->decimal(11)->notNull()->comment('Số tiền được chiết khấu'),
            'final_total' => $this->decimal(11)->notNull()->comment('Số tiền còn lại'),
            'description' => $this->text()->null()->comment('Mô tả'),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->createIndex('idx-slug', 'affiliate_order', 'slug', true);
        $this->addForeignKey('fk-affiliate_order__created_by-user__id', 'affiliate_order', 'created_by', 'user', 'id');
        $this->addForeignKey('fk-affiliate_order__updated_by-user__id', 'affiliate_order', 'updated_by', 'user', 'id');
        $this->addForeignKey('fk-affiliate_order__coupon_id-affiliate_coupon__id', 'affiliate_order', 'coupon_id', 'affiliate_coupon', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%affiliate_order}}');
    }
}
