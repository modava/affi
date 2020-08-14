<?php

use yii\db\Migration;

/**
 * Class m200814_094551_modifiy_table_affiliate_customer
 */
class m200814_094551_modifiy_table_affiliate_customer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('affiliate_customer', 'status', $this->integer(1)->notNull()->comment('Tình trạng KH: 0: Đang làm dịch vụ, 1: Đã hoàn thành dịch vụ'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200814_094551_modifiy_table_affiliate_customer cannot be reverted.\n";

        return false;
    }
    */
}
