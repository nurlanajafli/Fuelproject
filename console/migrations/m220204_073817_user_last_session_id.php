<?php

use yii\db\Migration;

/**
 * Class m220204_073817_user_last_session_id
 */
class m220204_073817_user_last_session_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'last_session_id', $this->string(32));
        $this->addColumn('{{%user}}', 'last_activity', $this->timestamp());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'last_session_id');
        $this->dropColumn('{{%user}}', 'last_activity');
    }
}
