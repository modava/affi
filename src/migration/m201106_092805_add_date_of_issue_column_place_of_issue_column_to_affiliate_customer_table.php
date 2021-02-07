<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%affiliate_customer}}`.
 */
class m201106_092805_add_date_of_issue_column_place_of_issue_column_to_affiliate_customer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('affiliate_customer', 'place_of_issue', $this->integer(11)->null());
        $this->addColumn('affiliate_customer', 'date_of_issue', $this->integer(11)->defaultValue(0));
        $this->addForeignKey('fk_affiliate_customer_place_of_issue_location_province', 'affiliate_customer', 'place_of_issue', 'location_province', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('affiliate_customer', 'place_of_issue');
        $this->dropColumn('affiliate_customer', 'date_of_issue');
        $this->dropForeignKey(
            'fk_affiliate_customer_place_of_issue_location_province',
            'affiliate_customer'
        );
    }
}
