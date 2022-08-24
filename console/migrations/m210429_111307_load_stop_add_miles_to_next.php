<?php

use yii\db\Migration;

/**
 * Class m210429_111307_load_stop_add_miles_to_next
 */
class m210429_111307_load_stop_add_miles_to_next extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%load_stop}}', 'miles_to_next', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%load_stop}}', 'miles_to_next');
    }
}
