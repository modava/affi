<?php

use yii\db\Migration;

/**
 * Class m200729_021203_create_table_call_note
 */
class m200729_021203_create_table_call_note extends Migration
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

        $this->createTable('{{%note}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull()->unique(),
            'partner_id' => $this->integer(11)->notNull()->comment('Partner tích hợp affiliate'),
            'customer_id' => $this->integer(11)->notNull()->comment('Mã khách hàng'),
            'call_time' => $this->dateTime()->notNull()->comment('Thời gian gọi'),
            'recall_time' => $this->dateTime()->notNull()->comment('Thời gian gọi lại'),
            'description' => $this->text()->null()->comment('Mô tả'),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'created_by' => $this->integer(11)->null()->comment('Người gọi'),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->createIndex('idx-slug', 'note', 'slug', true);
        $this->addForeignKey('fk-note-user_created-by_user-id', 'note', 'created_by', 'user', 'id');
        $this->addForeignKey('fk-note-user_updated-by_user-id', 'note', 'updated_by', 'user', 'id');
        $this->addForeignKey('fk_note_partner_id_partner_id', 'note', 'partner_id', 'partner', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%note}}');
    }
}
