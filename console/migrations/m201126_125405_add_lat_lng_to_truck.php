<?php

use yii\db\Migration;

/**
 * Class m201126_125405_add_lat_lng_to_truck
 */
class m201126_125405_add_lat_lng_to_truck extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%truck}}', 'lat', $this->float());
        $this->addColumn('{{%truck}}', 'lng', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%truck}}', 'lat');
        $this->dropColumn('{{%truck}}', 'lng');
    }
}
