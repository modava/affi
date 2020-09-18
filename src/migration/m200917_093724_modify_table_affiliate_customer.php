<?php

use modava\affiliate\models\search\PartnerSearch;
use yii\db\Migration;

/**
 * Class m200917_093724_modify_table_affiliate_customer
 */
class m200917_093724_modify_table_affiliate_customer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('affiliate_customer', 'partner_customer_id', $this->integer(11)->null()->comment('Id Khách hàng ở hệ thống partner'));

        if (PartnerSearch::getRecordBySlug('kols') === null) {
            $this->execute("INSERT INTO `affiliate_partner`(`title`, `slug`, `description`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES ('KOLs', 'kols', '', 1600328663, 1600328663, 1, 1);");
        }
        if (PartnerSearch::getRecordBySlug('partner') === null) {
            $this->execute("INSERT INTO `affiliate_partner`(`title`, `slug`, `description`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES ('Partner', 'partner', '', 1600328675, 1600328675, 1, 1);
");
        }


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200917_093724_modify_table_affiliate_customer cannot be reverted.\n";

        return false;
    }
}
