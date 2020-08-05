<?php

use yii\db\Migration;

/**
 * Class m200729_021203_create_table_affiliate_note
 */
class m200729_021203_create_table_affiliate_note extends Migration
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

        $this->createTable('{{%affiliate_note}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull()->unique(),
            'customer_id' => $this->integer(11)->notNull()->comment('Mã khách hàng'),
            'call_time' => $this->dateTime()->notNull()->comment('Thời gian gọi'),
            'recall_time' => $this->dateTime()->notNull()->comment('Thời gian gọi lại'),
            'description' => $this->text()->null()->comment('Mô tả'),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'created_by' => $this->integer(11)->null()->comment('Người gọi'),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->createIndex('idx-slug', 'affiliate_note', 'slug', true);
        $this->addForeignKey('fk-affiliate_note-user_created-by_user-id', 'affiliate_note', 'created_by', 'user', 'id');
        $this->addForeignKey('fk-affiliate_note-user_updated-by_user-id', 'affiliate_note', 'updated_by', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%affiliate_note}}');
    }
}
