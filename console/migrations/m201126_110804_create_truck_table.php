<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%truck}}`.
 */
class m201126_110804_create_truck_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%truck}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%truck}}');
    }
}
