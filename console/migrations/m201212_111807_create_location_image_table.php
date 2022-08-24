<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%location_image}}`.
 */
class m201212_111807_create_location_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%location_image}}', [
            'id' => $this->primaryKey(),
            'location_id' => $this->integer()->notNull(),
            'description' => $this->string(),
            'path' => $this->string()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%location_image_location_id_fk}}',
            '{{%location_image}}',
            'location_id',
            '{{%location}}',
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
            '{{%location_image_location_id_fk}}',
            '{{%location_image}}'
        );
        $this->dropTable('{{%location_image}}');
    }
}
