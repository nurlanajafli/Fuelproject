<?php

use yii\db\Migration;

/**
 * Class m210519_110322_load_add_actual_arrived_fields
 */
class m210519_110322_load_add_actual_arrived_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("{{%load}}", "arrived_date", $this->date());
        $this->addColumn("{{%load}}", "arrived_time_in", $this->time());
        $this->addColumn("{{%load}}", "arrived_time_out", $this->time());
        $this->addColumn("{{%load}}", "signed_for_by", $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%load}}", "arrived_time_out");
        $this->dropColumn("{{%load}}", "arrived_time_in");
        $this->dropColumn("{{%load}}", "arrived_date");
        $this->dropColumn("{{%load}}", "signed_for_by");
    }
}
