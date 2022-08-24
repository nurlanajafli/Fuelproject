<?php

use yii\db\Migration;

/**
 * Class m201119_123658_create_state
 */
class m201119_123658_create_state extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%state}}', [
            'id' => $this->primaryKey(),
            'state_code' => $this->string(),
            'state' => $this->string()->notNull(),
            'country_code' => $this->string()->notNull(),
            'country' => $this->string()->notNull(),
            'region' => $this->string()->notNull(),
        ]);
        $this->createIndex('{{%state_country_code_state_unique}}', '{{%state}}', ['country_code', 'state'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%state_country_code_state_unique}}', '{{%state}}');
        $this->dropTable('{{%state}}');
    }
}
