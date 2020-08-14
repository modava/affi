<?php

use yii\db\Migration;

/**
 * Class m200813_080332_modify_table_affiliate_note
 */
class m200813_080332_modify_table_affiliate_note extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Add column
        $this->addColumn('affiliate_note', 'partner_id', $this->integer(11));
        $this->addColumn('affiliate_note', 'partner_note_id', $this->integer(11));
        $this->addColumn('affiliate_note', 'note_type', $this->integer(1)->notNull()->comment('0: Hệ thống hiện tại, 1: Hệ thống partner (Nếu là 1 thì phải required parner_id'));

        // Add primary key
        $this->addForeignKey('fk-af_note__partner_id-af_partner__id', 'affiliate_note', 'partner_id', 'affiliate_partner', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200813_080332_modify_table_affiliate_note cannot be reverted.\n";

        return false;
    }
}
