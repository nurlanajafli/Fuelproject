<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%trailer_image}}`.
 */
class m201211_105530_create_trailer_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%trailer_image}}', [
            'id' => $this->primaryKey(),
            'trailer_id' => $this->integer()->notNull(),
            'description' => $this->string(),
            'path' => $this->string()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%trailer_image_trailer_id_fk}}',
            '{{%trailer_image}}',
            'trailer_id',
            '{{%trailer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%trailer_image_trailer_id_fk}}',
            '{{%trailer_image}}'
        );
        $this->dropTable('{{%trailer_image}}');
    }
}
