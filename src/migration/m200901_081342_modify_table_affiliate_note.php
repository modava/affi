<?php

use yii\db\Migration;

/**
 * Class m200901_081342_modify_table_affiliate_note
 */
class m200901_081342_modify_table_affiliate_note extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('affiliate_note', 'is_recall', $this->smallInteger(1)->defaultValue(0)->comment('Đã gọi lại'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200901_081342_modify_table_affiliate_note cannot be reverted.\n";

        return false;
    }
}
