<?php

use yii\db\Migration;

/**
 * Class m201120_080356_update_receipt_date_column_to_affiliate_receipt
 */
class m201120_080356_update_receipt_date_column_to_affiliate_receipt extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('update affiliate_receipt set receipt_date = created_at;');
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201120_080356_update_receipt_date_column_to_affiliate_receipt cannot be reverted.\n";
        
        return false;
    }
    
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201120_080356_update_receipt_date_column_to_affiliate_receipt cannot be reverted.\n";

        return false;
    }
    */
}
