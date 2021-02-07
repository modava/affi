<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%affiliate_receipt}}`.
 */
class m201119_083208_add_receipt_date_column_to_affiliate_receipt_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('affiliate_receipt', 'receipt_date', $this->integer(11)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('affiliate_receipt', 'receipt_date');
    }
}
