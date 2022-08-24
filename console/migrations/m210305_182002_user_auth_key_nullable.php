<?php

use yii\db\Migration;

/**
 * Class m210305_182002_user_auth_key_nullable
 */
class m210305_182002_user_auth_key_nullable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%user}}', 'auth_key', $this->string(40));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%user}}', 'auth_key', $this->string(32)->notNull());
    }
}
