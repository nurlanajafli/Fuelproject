<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%office}}`.
 */
class m201125_145257_create_office_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%office}}', [
            'id' => $this->primaryKey(),
            'office' => $this->string()->notNull(),
            'city' => $this->string()->notNull(),
            'state_id' => $this->integer(),
        ]);
        $this->addForeignKey(
            '{{%office_state_id_fk}}',
            '{{%office}}',
            'state_id',
            '{{%state}}',
            'id',
            'RESTRICT',
            'RESTRICT',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%office_state_id_fk}}', '{{%office}}');
        $this->dropTable('{{%office}}');
    }
}
