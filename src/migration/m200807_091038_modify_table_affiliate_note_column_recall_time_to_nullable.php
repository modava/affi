<?php

use yii\db\Migration;

/**
 * Class m200807_091038_modify_table_affiliate_note_column_recall_time_to_nullable
 */
class m200807_091038_modify_table_affiliate_note_column_recall_time_to_nullable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE affiliate_note MODIFY recall_time datetime null;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200807_091038_modify_table_affiliate_note_column_recall_time_to_nullable cannot be reverted.\n";

        return false;
    }
}
