<?php

use yii\db\Migration;

/**
 * Class m200727_035943_create_table_coupon_type
 */
class m200727_035943_create_table_coupon_type extends Migration
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

        $this->createTable('{{%coupon_type}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull()->unique(),
            'description' => $this->text()->null(),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->createIndex('idx-slug', 'coupon_type', 'slug', true);
        $this->addForeignKey('fk-coupon-type-user_created-by_user-id', 'coupon_type', 'created_by', 'user', 'id');
        $this->addForeignKey('fk-coupon-type-user_updated-by_user-id', 'coupon_type', 'updated_by', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%coupon_type}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200727_035943_create_table_coupon_type cannot be reverted.\n";

        return false;
    }
    */
}
