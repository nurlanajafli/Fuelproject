<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%carrier_image}}`.
 */
class m201211_095601_create_carrier_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%carrier_image}}', [
            'id' => $this->primaryKey(),
            'carrier_id' => $this->integer()->notNull(),
            'description' => $this->string(),
            'path' => $this->string()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%carrier_image_carrier_id_fk}}',
            '{{%carrier_image}}',
            'carrier_id',
            '{{%carrier}}',
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
            '{{%carrier_image_carrier_id_fk}}',
            '{{%carrier_image}}'
        );
        $this->dropTable('{{%carrier_image}}');
    }
}
