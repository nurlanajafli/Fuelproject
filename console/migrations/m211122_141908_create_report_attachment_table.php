<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%report_attachment}}`.
 */
class m211122_141908_create_report_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%report_media}}', [
            'id' => $this->primaryKey(),
            'report_id' => $this->integer()->notNull(),
            'side' => $this->string()->notNull(),
            'is_major' => $this->boolean()->notNull(),
            'is_interior' => $this->boolean()->notNull(),
            'description' => $this->string(),
            'mime_type' => $this->string()->notNull(),
            'file' => $this->string()->notNull(),
            'width' => $this->integer(),
            'height' => $this->integer(),
            'size' => $this->integer(),
            'damage_type' => $this->string(),
            'created_at' => $this->timestamp()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->timestamp(),
            'updated_by' => $this->integer(),
        ]);
        $this->addForeignKey('{{%report_media__report_fk}}', '{{%report_media}}', 'report_id', '{{%report}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%report_media__created_by_fk}}', '{{%report_media}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%report_media__updated_by_fk}}', '{{%report_media}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%report_media__report_fk}}', '{{%report_media}}');
        $this->dropForeignKey('{{%report_media__created_by_fk}}', '{{%report_media}}');
        $this->dropForeignKey('{{%report_media__updated_by_fk}}', '{{%report_media}}');
        $this->dropTable('{{%report_media}}');
    }
}
