<?php

use yii\db\Migration;

/**
 * Class m200826_102831_create_table_affiliate_receipt
 */
class m200826_102831_create_table_affiliate_receipt extends Migration
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

        $this->createTable('{{%affiliate_receipt}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(255)->notNull()->unique(),
            'title' => $this->string(255)->notNull(),
            'order_id' => $this->integer(11)->notNull()->comment('Mã đơn hàng'),
            'total' => $this->decimal(11)->notNull()->comment('Số tiền'),
            'status' => $this->smallInteger(2)->notNull()->comment('0: Thanh toán, 1: Đặt cọc, 2: Hoàn cọc'),
            'payment_method' => $this->string()->comment('Phương thức thanh toán'),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->createIndex('idx-slug', 'affiliate_receipt', 'slug', true);
        $this->addForeignKey('fk-affiliate_receipt__created_by-user__id', 'affiliate_receipt', 'created_by', 'user', 'id');
        $this->addForeignKey('fk-affiliate_receipt__updated_by-user__id', 'affiliate_receipt', 'updated_by', 'user', 'id');
        $this->addForeignKey('fk_af_receipt_order_id_af_order_id', 'affiliate_receipt', 'order_id', 'affiliate_order', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%affiliate_receipt}}');
    }
}
