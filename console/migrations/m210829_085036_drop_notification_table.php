<?php

use yii\db\Migration;

/**
 * Class m210829_085036_chat_message_add_bot
 */
class m210829_085036_drop_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $baseTable = 'notification';
        $table = "{{%$baseTable}}";
        $this->dropForeignKey(
            "{{%{$baseTable}_office_id_fk}}",
            $table
        );
        $this->dropForeignKey(
            "{{%{$baseTable}_created_by_fk}}",
            $table
        );
        $this->dropTable($table);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $baseTable = 'notification';
        $this->createTable($table = "{{%$baseTable}}", [
            'id' => $this->primaryKey(),
            'subject' => $this->string()->notNull(),
            'details' => $this->string()->notNull(),
            'params' => $this->string(),
            'seen' => $this->boolean()->notNull()->defaultValue(false),
            'office_id' => $this->integer()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => 'timestamp with time zone',
        ]);
        $this->addForeignKey(
            "{{%{$baseTable}_office_id_fk}}",
            $table,
            'office_id',
            '{{%office}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseTable}_created_by_fk}}",
            $table,
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }
}
