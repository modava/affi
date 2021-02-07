<?php

use yii\db\Migration;

/**
 * Class m200827_100703_create_table_phonebook
 */
class m200827_100703_create_table_phonebook extends Migration
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

        $this->createTable('{{%affiliate_phonebook}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull()->unique(),
            'phone' => $this->string(20)->notNull(),
            'status' => $this->smallInteger(1),
            'description' => $this->text()->null(),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->createIndex('idx-slug', 'affiliate_phonebook', 'slug', true);
        $this->addForeignKey('fk-affiliate_phonebook-user_created-by_user-id', 'affiliate_phonebook', 'created_by', 'user', 'id');
        $this->addForeignKey('fk-affiliate_phonebook-user_updated-by_user-id', 'affiliate_phonebook', 'updated_by', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
