<?php

use yii\db\Migration;

/**
 * Class m210401_160831_location_add_time_zone
 */
class m210401_160831_location_add_time_zone extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%location}}', 'time_zone', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%location}}', 'time_zone');
    }
}
