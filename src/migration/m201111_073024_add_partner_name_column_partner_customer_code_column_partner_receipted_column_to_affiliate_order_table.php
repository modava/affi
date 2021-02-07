<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%affiliate_order}}`.
 */
class m201111_073024_add_partner_name_column_partner_customer_code_column_partner_receipted_column_to_affiliate_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('affiliate_order', 'partner_name', $this->string(255)->comment('Tên khách hàng hệ thống partner'));
        $this->addColumn('affiliate_order', 'partner_customer_code', $this->string(255)->comment('Mã khách hàng hệ thống partner'));
        $this->addColumn('affiliate_order', 'partner_receipted', $this->decimal(11)->defaultValue(0)->comment('Đã thu'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('affiliate_order', 'partner_name');
        $this->dropColumn('affiliate_order', 'partner_customer_code');
        $this->dropColumn('affiliate_order', 'partner_receipted');
    }
}
