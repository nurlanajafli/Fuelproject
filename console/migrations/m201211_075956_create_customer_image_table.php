<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer_image}}`.
 */
class m201211_075956_create_customer_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%customer_image}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer()->notNull(),
            'description' => $this->string(),
            'path' => $this->string()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%customer_image_customer_id_fk}}',
            '{{%customer_image}}',
            'customer_id',
            '{{%customer}}',
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
            '{{%customer_image_customer_id_fk}}',
            '{{%customer_image}}'
        );
        $this->dropTable('{{%customer_image}}');
    }
}
