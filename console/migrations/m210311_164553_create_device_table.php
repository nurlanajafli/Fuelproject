<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%device}}`.
 */
class m210311_164553_create_device_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $baseTable = 'device';
        $this->createTable($table = "{{%$baseTable}}", [
            'id' => $this->string()->notNull(),
            'os' => $this->string()->notNull(),
            'version' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => 'timestamp with time zone',
            'updated_at' => 'timestamp with time zone',
        ]);
        $this->addPrimaryKey("{{%{$baseTable}_id_pk}}", $table, 'id');
        $this->addForeignKey(
            "{{%{$baseTable}_user_id_fk}}",
            $table,
            'user_id',
            '{{%user}}',
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
        $baseTable = 'device';
        $table = "{{%$baseTable}}";
        $this->dropPrimaryKey("{{%{$baseTable}_id_pk}}", $table);
        $this->dropForeignKey(
            "{{%{$baseTable}_user_id_fk}}",
            $table
        );
        $this->dropTable($table);
    }
}
