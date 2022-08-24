<?php

use yii\db\Migration;

/**
 * Class m210326_101613_change_load_note_last_action_column
 */
class m210326_101613_change_load_note_last_action_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%load_note}}', 'last_action', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%load_note}}', 'last_action', $this->integer()->notNull());
    }
}
