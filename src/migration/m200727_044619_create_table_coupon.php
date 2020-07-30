<?php

use yii\db\Migration;

/**
 * Class m200727_044619_create_table_coupon
 */
class m200727_044619_create_table_coupon extends Migration
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

        $this->createTable('{{%affiliate_coupon}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull()->unique(),
            'coupon_code' => $this->string(25)->notNull()->unique()->comment('Mã coupon'),
            'quantity' => $this->integer(10)->notNull()->comment('Số lượng coupon'),
            'expired_date' => $this->dateTime()->null()->comment('Ngày hết hạn coupon'),
            'description' => $this->text()->null()->comment('Mô tả'),
            'customer_id' => $this->integer(11)->notNull()->comment('Mã khách hàng'),
            'coupon_type_id' => $this->integer(11)->notNull()->comment('Mã coupon'),
            'quantity_used' => $this->integer()->defaultValue(0)->notNull()->comment('Số lượng đã sử dụng'),
            'promotion_type' => $this->smallInteger(3)->notNull()->comment('Loại khuyễn mại'),
            'promotion_value' => $this->integer(11)->notNull()->comment('Giá trị coupon'),
            'partner_id' => $this->integer(11)->notNull()->comment('Partner tích hợp affiliate'),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->createIndex('idx-slug', 'affiliate_coupon', 'slug', true);
        $this->addForeignKey('fk_coupon_coupon_type_id_coupon_type_id', 'affiliate_coupon', 'coupon_type_id', 'affiliate_coupon_type', 'id');
        $this->addForeignKey('fk-coupon-user_created-by_user-id', 'affiliate_coupon', 'created_by', 'user', 'id');
        $this->addForeignKey('fk-coupon-user_updated-by_user-id', 'affiliate_coupon', 'updated_by', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%affiliate_coupon}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200727_044619_create_table_coupon cannot be reverted.\n";

        return false;
    }
    */
}
