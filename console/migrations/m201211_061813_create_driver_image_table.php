<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%driver_image}}`.
 */
class m201211_061813_create_driver_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%driver_image}}', [
            'id' => $this->primaryKey(),
            'driver_id' => $this->integer()->notNull(),
            'description' => $this->string(),
            'path' => $this->string()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%driver_image_driver_id_fk}}',
            '{{%driver_image}}',
            'driver_id',
            '{{%driver}}',
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
            '{{%driver_image_driver_id_fk}}',
            '{{%driver_image}}'
        );
        $this->dropTable('{{%driver_image}}');
    }
}
