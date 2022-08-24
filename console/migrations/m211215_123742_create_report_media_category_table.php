<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%report_media_category}}`.
 */
class m211215_123742_create_report_media_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%report_media_category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'description' => $this->string(),
            'created_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'updated_at' => $this->timestamp(),
            'updated_by' => $this->integer(),
        ]);
        $this->addColumn('{{%report_media}}', 'category_id', $this->integer());
        $this->addForeignKey('{{%report_media_category_id_fk}}', '{{%report_media}}', 'category_id', '{{%report_media_category}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%report_media_created_by_fk}}', '{{%report_media_category}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%report_media_updated_by_fk}}', '{{%report_media_category}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
        $this->batchInsert('{{%report_media_category}}', ['name', 'created_at'], [
            ['Tire', new \yii\db\Expression('LOCALTIMESTAMP')],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%report_media_category_id_fk}}', '{{%report_media}}');
        $this->dropForeignKey('{{%report_media_created_by_fk}}', '{{%report_media_category}}');
        $this->dropForeignKey('{{%report_media_updated_by_fk}}', '{{%report_media_category}}');
        $this->dropColumn('{{%report_media}}', 'category_id');
        $this->dropTable('{{%report_media_category}}');
    }
}
