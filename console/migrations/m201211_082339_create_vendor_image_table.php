<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vendor_image}}`.
 */
class m201211_082339_create_vendor_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vendor_image}}', [
            'id' => $this->primaryKey(),
            'vendor_id' => $this->integer()->notNull(),
            'description' => $this->string(),
            'path' => $this->string()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%vendor_image_vendor_id_fk}}',
            '{{%vendor_image}}',
            'vendor_id',
            '{{%vendor}}',
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
            '{{%vendor_image_vendor_id_fk}}',
            '{{%vendor_image}}'
        );
        $this->dropTable('{{%vendor_image}}');
    }
}
