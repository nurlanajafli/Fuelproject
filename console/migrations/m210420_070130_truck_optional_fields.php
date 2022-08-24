<?php

use yii\db\Migration;

/**
 * Class m210420_070130_truck_optional_fields
 */
class m210420_070130_truck_optional_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%truck}}', 'truck_no', $this->string()->notNull());
        $this->alterColumn('{{%truck}}', 'year', $this->integer());
        $this->alterColumn('{{%truck}}', 'make', $this->string());
        $this->alterColumn('{{%truck}}', 'model', $this->string());
        $this->alterColumn('{{%truck}}', 'serial', $this->string());
        $this->alterColumn('{{%truck}}', 'vin', $this->string());
        $this->alterColumn('{{%truck}}', 'tare', $this->integer());
        $this->alterColumn('{{%truck}}', 'license', $this->string());
        $this->alterColumn('{{%truck}}', 'license_state_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%truck}}', 'license_state_id', $this->integer()->notNull());
        $this->alterColumn('{{%truck}}', 'license', $this->string()->notNull());
        $this->alterColumn('{{%truck}}', 'tare', $this->integer()->notNull());
        $this->alterColumn('{{%truck}}', 'vin', $this->string()->notNull());
        $this->alterColumn('{{%truck}}', 'serial', $this->string()->notNull());
        $this->alterColumn('{{%truck}}', 'model', $this->string()->notNull());
        $this->alterColumn('{{%truck}}', 'make', $this->string()->notNull());
        $this->alterColumn('{{%truck}}', 'year', $this->integer()->notNull());
        $this->alterColumn('{{%truck}}', 'truck_no', $this->string());
    }
}
