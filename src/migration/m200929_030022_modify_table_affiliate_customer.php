<?php

use yii\db\Migration;

/**
 * Class m200929_030022_modify_table_affiliate_customer
 */
class m200929_030022_modify_table_affiliate_customer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn('affiliate_customer', 'id_card_number', $this->string('20')->null()->comment('CMND/CTCD'));
        $this->addColumn('affiliate_customer', 'payment_type', $this->smallInteger(1)->null()->comment('Phương thức chi: Chuyển khoản, Tiền mặt'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200929_030022_modify_table_affiliate_customer cannot be reverted.\n";

        return false;
    }
}
