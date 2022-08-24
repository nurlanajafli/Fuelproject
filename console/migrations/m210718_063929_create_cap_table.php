<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cap}}`.
 */
class m210718_063929_create_cap_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cap}}', [
            'id' => $this->primaryKey(),
            'description' => $this->string()->notNull()->unique()
        ]);
        $this->batchInsert('{{%cap}}', ['id', 'description'], [[1, 'Post Until Account Balance Reaches 0'], [2, 'Post Until Account Balance Reaches Cap']]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cap}}');
    }
}
