<?php

use yii\db\Migration;

/**
 * Class m210211_053744_truck_add_truck_no
 */
class m210211_053744_truck_add_truck_no extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%truck}}', 'truck_no', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%truck}}', 'truck_no');
    }
}
