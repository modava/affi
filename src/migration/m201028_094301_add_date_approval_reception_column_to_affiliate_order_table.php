<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%affiliate_order}}`.
 */
class m201028_094301_add_date_approval_reception_column_to_affiliate_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
	public function safeUp()
	{
		$this->addColumn('affiliate_order', 'date_approval_reception', $this->integer(11)->defaultValue(0));
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn('affiliate_order', 'date_approval_reception');
	}
}
