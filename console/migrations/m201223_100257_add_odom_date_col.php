<?php

use yii\db\Migration;

/**
 * Class m201223_100257_add_odom_date_col
 */
class m201223_100257_add_odom_date_col extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%truck_odometer}}', 'date_collected', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%truck_odometer}}', 'date_collected');
    }
}
