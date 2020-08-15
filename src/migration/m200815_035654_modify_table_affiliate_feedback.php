<?php

use yii\db\Migration;

/**
 * Class m200815_035654_modify_table_feedback
 */
class m200815_035654_modify_table_affiliate_feedback extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('affiliate_feedback', 'satisfied_feedback', $this->text()->null()->comment('Feedback hài lòng'));
        $this->execute("ALTER TABLE affiliate_feedback MODIFY unsatisfied_reason_id int(11) null;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('affiliate_feedback', 'satisfied_feedback');
    }
}
