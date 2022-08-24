<?php

use yii\db\Migration;

/**
 * Class m210713_113444_load_stop_add_status
 */
class m210713_113444_load_stop_add_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%load_stop}}', 'status', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%load_stop}}', 'status');
    }
}
