<?php

use yii\db\Migration;

/**
 * Class m210517_073404_chat_tables
 */
class m210517_073404_chat_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = '{{%chat_message}}';
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'reply_id' => $this->integer(),
            'driver_id' => $this->integer(),
            'load_id' => $this->integer(),
            'message' => $this->string(4096)->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey(
            substr($table, 0, -2) . '_reply_id_fk}}',
            $table,
            'reply_id',
            $table,
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            substr($table, 0, -2) . '_driver_id_fk}}',
            $table,
            'driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            substr($table, 0, -2) . '_load_id_fk}}',
            $table,
            'load_id',
            '{{%load}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            substr($table, 0, -2) . '_created_by_fk}}',
            $table,
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $table = '{{%chat_message_seen}}';
        $this->createTable($table, [
            'message_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
        ]);
        $this->addForeignKey(
            substr($table, 0, -2) . '_message_id_fk}}',
            $table,
            'message_id',
            '{{%chat_message}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            substr($table, 0, -2) . '_user_id_fk}}',
            $table,
            'user_id',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->createIndex(
            substr($table, 0, -2) . '_message_id_user_id_unique}}',
            $table,
            ['message_id', 'user_id'],
            true
        );

        $table = '{{%department}}';
        $this->addColumn($table, 'parent_id', $this->integer());
        $this->addForeignKey(
            substr($table, 0, -2) . '_parent_id_fk}}',
            $table,
            'parent_id',
            $table,
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $table = '{{%user}}';
        $this->addColumn($table, 'department_id', $this->integer());
        $this->addForeignKey(
            substr($table, 0, -2) . '_department_id_fk}}',
            $table,
            'department_id',
            '{{%department}}',
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
        $table = '{{%chat_message_seen}}';
        $this->dropForeignKey(
            substr($table, 0, -2) . '_message_id_fk}}',
            $table
        );
        $this->dropForeignKey(
            substr($table, 0, -2) . '_user_id_fk}}',
            $table
        );
        $this->dropIndex(
            substr($table, 0, -2) . '_message_id_user_id_unique}}',
            $table
        );
        $this->dropTable($table);

        $table = '{{%chat_message}}';
        $this->dropForeignKey(
            substr($table, 0, -2) . '_reply_id_fk}}',
            $table
        );
        $this->dropForeignKey(
            substr($table, 0, -2) . '_driver_id_fk}}',
            $table
        );
        $this->dropForeignKey(
            substr($table, 0, -2) . '_load_id_fk}}',
            $table
        );
        $this->dropForeignKey(
            substr($table, 0, -2) . '_created_by_fk}}',
            $table
        );
        $this->dropTable($table);

        $table = '{{%department}}';
        $this->dropForeignKey(
            substr($table, 0, -2) . '_parent_id_fk}}',
            $table
        );
        $this->dropColumn($table, 'parent_id');

        $table = '{{%user}}';
        $this->dropForeignKey(
            substr($table, 0, -2) . '_department_id_fk}}',
            $table
        );
        $this->dropColumn($table, 'department_id');
    }
}
