<?php

use yii\db\Migration;

/**
 * Class m210508_100008_mail_tables
 */
class m210508_100008_mail_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey(
            '{{%mail_message_reply_id_fk}}',
            '{{%mail_message}}'
        );
        $this->dropForeignKey(
            '{{%mail_message_user_id_fk}}',
            '{{%mail_message}}'
        );
        $this->dropForeignKey(
            '{{%mail_message_driver_id_fk}}',
            '{{%mail_message}}'
        );
        $this->dropTable('{{%mail_message}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%mail_message}}', [
            'id' => $this->primaryKey(),
            'reply_id' => $this->integer(),
            'subject' => $this->string()->notNull(),
            'message' => $this->text(),
            'status' => $this->string()->notNull(),
            'user_id' => $this->integer(),
            'driver_id' => $this->integer(),
            'utd' => $this->boolean()->notNull()->comment('either the message is from the user to the driver or vice versa'),
            'created_at' => 'timestamp with time zone',
        ]);
        $this->addForeignKey(
            '{{%mail_message_reply_id_fk}}',
            '{{%mail_message}}',
            'reply_id',
            '{{%mail_message}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%mail_message_user_id_fk}}',
            '{{%mail_message}}',
            'user_id',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%mail_message_driver_id_fk}}',
            '{{%mail_message}}',
            'driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }
}
