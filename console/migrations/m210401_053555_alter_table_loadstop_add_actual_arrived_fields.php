<?php

use yii\db\Migration;

/**
 * Class m210401_053555_alter_table_loadstop_add_actual_arrived_fields
 */
class m210401_053555_alter_table_loadstop_add_actual_arrived_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("{{%load_stop}}", "arrived_date", $this->date());
        $this->addColumn("{{%load_stop}}", "arrived_time_in", $this->time());
        $this->addColumn("{{%load_stop}}", "arrived_time_out", $this->time());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%load_stop}}", "arrived_time_out");
        $this->dropColumn("{{%load_stop}}", "arrived_time_in");
        $this->dropColumn("{{%load_stop}}", "arrived_date");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210401_053555_alter_table_loadstop_add_actual_arrived_fields cannot be reverted.\n";

        return false;
    }
    */
}
