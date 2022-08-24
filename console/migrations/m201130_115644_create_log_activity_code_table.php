<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%log_activity_code}}`.
 */
class m201130_115644_create_log_activity_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%log_activity_code}}', [
            'code' => $this->primaryKey(),
            'description' => $this->string(),
        ]);
        $this->batchInsert('{{%log_activity_code}}', ['code', 'description'], [
            [10, 'Tire Check'],
            [20, 'Load Check'],
            [30, 'Brake Check'],
            [40, 'PTI'],
        ]);
        $this->alterColumn('{{%accident_code}}', 'description', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%accident_code}}', 'description', $this->string()->notNull());
        $this->dropTable('{{%log_activity_code}}');
    }
}
