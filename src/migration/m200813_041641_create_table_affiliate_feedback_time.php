<?php

use yii\db\Migration;

/**
 * Class m200813_041641_create_table_affiliate_feedback_time
 */
class m200813_041641_create_table_affiliate_feedback_time extends Migration
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

        $this->createTable('{{%affiliate_feedback_time}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull()->unique(),
            'description' => $this->text()->null()->comment('Mô tả'),
            'status' => $this->integer(1)->comment('0: Tạm ngưng, 1: Hiển thị'),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->createIndex('idx-slug', 'affiliate_feedback_time', 'slug', true);
        $this->addForeignKey('fk-affiliate_feedback_time-user_created-by_user-id', 'affiliate_feedback_time', 'created_by', 'user', 'id');
        $this->addForeignKey('fk-affiliate_feedback_time-user_updated-by_user-id', 'affiliate_feedback_time', 'updated_by', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%affiliate_feedback_time}}');
    }
}
