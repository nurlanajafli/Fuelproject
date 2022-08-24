<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%fuel_purchase}}`.
 */
class m211103_123634_create_fuel_purchase_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%fuel_purchase}}', [
            'id' => $this->primaryKey(),
            'trip_no' => $this->string(),
            'unit_id' => $this->integer(),
            'driver_id' => $this->integer(),
            'codriver_id' => $this->integer(),
            'truck_id' => $this->integer(),
            'trailer_id' => $this->integer(),
            'trailer2_id' => $this->integer(),
            'purchase_date' => $this->date(),
            'purchase_time' => $this->time(),
            'vendor' => $this->string(),
            'city' => $this->string(),
            'state_id' => $this->integer()->notNull(),
            'quantity' => $this->decimal(10, 2),
            'cost' => $this->decimal(10, 2),
            'ppg' => $this->decimal(10, 2),
            'created_at' => $this->timestamp()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->timestamp(),
            'updated_by' => $this->integer(),
        ]);
        $this->addForeignKey('{{%fuel_purchase__unit_id_fk}}', '{{%fuel_purchase}}', 'unit_id', '{{%unit}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%fuel_purchase__driver_id_fk}}', '{{%fuel_purchase}}', 'driver_id', '{{%driver}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%fuel_purchase__codriver_id_fk}}', '{{%fuel_purchase}}', 'codriver_id', '{{%driver}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%fuel_purchase__truck_id_fk}}', '{{%fuel_purchase}}', 'truck_id', '{{%truck}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%fuel_purchase__trailer_id_fk}}', '{{%fuel_purchase}}', 'trailer_id', '{{%trailer}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%fuel_purchase__trailer2_id_fk}}', '{{%fuel_purchase}}', 'trailer2_id', '{{%trailer}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%fuel_purchase__state_id_fk}}', '{{%fuel_purchase}}', 'state_id', '{{%state}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%fuel_purchase__created_by_fk}}', '{{%fuel_purchase}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%fuel_purchase__updated_by_fk}}', '{{%fuel_purchase}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fuel_purchase__unit_id_fk}}', '{{%fuel_purchase}}');
        $this->dropForeignKey('{{%fuel_purchase__driver_id_fk}}', '{{%fuel_purchase}}');
        $this->dropForeignKey('{{%fuel_purchase__codriver_id_fk}}', '{{%fuel_purchase}}');
        $this->dropForeignKey('{{%fuel_purchase__truck_id_fk}}', '{{%fuel_purchase}}');
        $this->dropForeignKey('{{%fuel_purchase__trailer_id_fk}}', '{{%fuel_purchase}}');
        $this->dropForeignKey('{{%fuel_purchase__trailer2_id_fk}}', '{{%fuel_purchase}}');
        $this->dropForeignKey('{{%fuel_purchase__state_id_fk}}', '{{%fuel_purchase}}');
        $this->dropForeignKey('{{%fuel_purchase__created_by_fk}}', '{{%fuel_purchase}}');
        $this->dropForeignKey('{{%fuel_purchase__updated_by_fk}}', '{{%fuel_purchase}}');
        $this->dropTable('{{%fuel_purchase}}');
    }
}
