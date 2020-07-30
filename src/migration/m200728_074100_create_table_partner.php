<?php

use yii\db\Migration;

/**
 * Class m200728_074100_create_table_partner
 */
class m200728_074100_create_table_partner extends Migration
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

        $this->createTable('{{%partner}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull()->unique(),
            'description' => $this->text()->null()->comment('Mô tả'),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->createIndex('idx-slug', 'partner', 'slug', true);
        $this->addForeignKey('fk-partner-user_created-by_user-id', 'partner', 'created_by', 'user', 'id');
        $this->addForeignKey('fk-partner-user_updated-by_user-id', 'partner', 'updated_by', 'user', 'id');
        $this->addForeignKey('fk_coupon_partner_id_partner_id', 'coupon', 'partner_id', 'partner', 'id');
        $this->execute('INSERT INTO `partner` (`id`, `title`, `slug`, `description`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (\'1\', \'Dashboard MyAuris\', \'dashboard-myauris\', \'<p>Link: http://dashboard.myauris.vn/</p>\', \'1596092085\', \'1596092085\', \'1\', \'1\');');
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%partner}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200728_074100_create_table_partner cannot be reverted.\n";

        return false;
    }
    */
}
