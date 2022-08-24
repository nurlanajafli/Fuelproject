<?php

use yii\db\Migration;

/**
 * Class m210305_161030_user_add_scope
 */
class m210305_161030_user_add_scope extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'scope', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'scope');
    }
}
