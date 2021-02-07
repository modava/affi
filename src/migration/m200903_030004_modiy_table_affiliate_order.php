<?php

use yii\db\Migration;

/**
 * Class m200903_030004_modiy_table_affiliate_order
 */
class m200903_030004_modiy_table_affiliate_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('affiliate_order', 'other_discount', $this->decimal(11)->defaultValue(0)->comment('Các khuyến mãi khác'));
        $this->dropForeignKey('fk_af_receipt_order_id_af_order_id', 'affiliate_receipt');
        $this->addForeignKey('fk_af_receipt_order_id_af_order_id', 'affiliate_receipt', 'order_id', 'affiliate_order', 'id','CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200903_030004_modiy_table_affiliate_order cannot be reverted.\n";

        return false;
    }
}
