<?php

use yii\db\Migration;

/**
 * Class m200813_041652_create_table_affiliate_feedback
 */
class m200813_041652_create_table_affiliate_feedback extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%affiliate_feedback}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull()->unique(),
            'customer_id' => $this->integer(11)->notNull()->comment('Khách hàng'),
            'unsatisfied_reason_id' => $this->integer(11)->notNull()->comment('Lý do không hài lòng'),
            'feedback_time_id' => $this->integer(11)->notNull()->comment('Thời gian Feedback: 1. Tháng, 3. Tháng, 6. Tháng ...'),
            'feedback_type' => $this->integer(1)->notNull()->comment('0: Không hài lòng, 1: Hài lòng'),
            'description' => $this->text()->null()->comment('Mô tả'),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->createIndex('idx-slug', 'affiliate_feedback', 'slug', true);
        $this->addForeignKey('fk-affiliate_feedback-user_created-by_user-id', 'affiliate_feedback', 'created_by', 'user', 'id');
        $this->addForeignKey('fk-affiliate_feedback-user_updated-by_user-id', 'affiliate_feedback', 'updated_by', 'user', 'id');
        $this->addForeignKey('fk-af_feedback-customer_id-af_customer__id', 'affiliate_feedback', 'customer_id', 'affiliate_customer', 'id');
        $this->addForeignKey('fk-af_feedback-unsatisfied_reason_id-af_unsatisfied_reason__id', 'affiliate_feedback', 'unsatisfied_reason_id', 'affiliate_unsatisfied_reason', 'id');
        $this->addForeignKey('fk-af_feedback-feedback_time_id-af_feedback_time__id', 'affiliate_feedback', 'feedback_time_id', 'affiliate_feedback_time', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%affiliate_feedback}}');
    }
}
