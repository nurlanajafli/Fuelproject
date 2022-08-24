<?php

use yii\db\Migration;

/**
 * Class m220214_095047_add_lat_lon_to_table_load_stop
 */
class m220214_095047_add_lat_lon_to_table_load_stop extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('load_stop', 'lat', $this->double());
        $this->addColumn('load_stop', 'lon',  $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('load_stop', 'lat');
        $this->dropColumn('load_stop', 'lon');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220214_095047_add_lat_lon_to_table_load_stop cannot be reverted.\n";

        return false;
    }
    */
}
