<?php

use yii\db\Migration;

/**
 * Class m200810_025711_create_table_affiliate_faq
 */
class m200810_030306_create_table_affiliate_faq extends Migration
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

        $this->createTable('{{%affiliate_faq}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull()->unique(),
            'content' => $this->text()->null()->comment('Câu trả lời'),
            'short_content' => $this->string(255)->comment('Câu trả lời ngắn'),
            'publish' => $this->integer(1)->defaultValue(1)->comment('0: Không hiển thị, 1: Hiển thị'),
            'faq_category_id' => $this->integer(11)->notNull(),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->createIndex('idx-slug', 'affiliate_faq', 'slug', true);
        $this->addForeignKey('fk-affiliate_faq__created_by-user__id', 'affiliate_faq', 'created_by', 'user', 'id');
        $this->addForeignKey('fk-affiliate_faq__updated_by-user__id', 'affiliate_faq', 'updated_by', 'user', 'id');
        $this->addForeignKey('fk-affiliate_faq__faq_category_id-affiliate_faq_category__id', 'affiliate_faq', 'faq_category_id', 'affiliate_faq_category', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%affiliate_faq}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200810_025711_create_table_affiliate_faq cannot be reverted.\n";

        return false;
    }
    */
}
