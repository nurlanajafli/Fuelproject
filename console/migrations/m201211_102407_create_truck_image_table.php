<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%truck_image}}`.
 */
class m201211_102407_create_truck_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%truck_image}}', [
            'id' => $this->primaryKey(),
            'truck_id' => $this->integer()->notNull(),
            'description' => $this->string(),
            'path' => $this->string()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%truck_image_truck_id_fk}}',
            '{{%truck_image}}',
            'truck_id',
            '{{%truck}}',
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
            '{{%truck_image_truck_id_fk}}',
            '{{%truck_image}}'
        );
        $this->dropTable('{{%truck_image}}');
    }
}
