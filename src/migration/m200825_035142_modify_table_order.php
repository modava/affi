<?php

use yii\db\Migration;

/**
 * Class m200825_035142_modify_table_order
 */
class m200825_035142_modify_table_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('affiliate_order', 'partner_customer_id', $this->integer(11)->null()->comment('Id khách hàng hệ thống partner'));
        $this->addColumn('affiliate_order', 'partner_order_code', $this->string()->null()->comment('Mã đơn hàng hệ thống partner'));
        $this->addColumn('affiliate_order', 'date_create', $this->integer(11)->notNull());
        $this->addColumn('affiliate_order', 'status', $this->smallInteger(2)->comment('0: Chưa hoàn thành, 1: Hoàn Thành, 2: Hủy'));
        $this->addColumn('affiliate_order', 'payment_method', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200825_035142_modify_table_order cannot be reverted.\n";

        return false;
    }

}
