<?php

use yii\db\Migration;

/**
 * Class m210918_125518_chat_message_drop_driver_id
 */
class m210918_125518_chat_message_drop_driver_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('{{%chat_message_driver_id_fk}}', '{{%chat_message}}');
        $this->dropColumn('{{%chat_message}}', 'driver_id');

        $this->addColumn('{{%chat_message}}', 'user_id', $this->integer());
        $this->addForeignKey(
            '{{%chat_message__user}}',
            '{{%chat_message}}',
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
        $this->addColumn('{{%chat_message}}', 'driver_id', $this->integer());
        $this->addForeignKey(
            '{{%chat_message_driver_id_fk}}',
            '{{%chat_message}}',
            'driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->dropForeignKey(
            '{{%chat_message__user}}',
            '{{%chat_message}}'
        );
        $this->dropColumn('{{%chat_message}}', 'user_id');
    }
}
