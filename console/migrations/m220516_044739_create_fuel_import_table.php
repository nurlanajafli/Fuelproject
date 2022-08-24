<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%fuel_import}}`.
 */
class m220516_044739_create_fuel_import_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%fuel_import}}', [
            'id' => $this->primaryKey(),
            'transaction_id' => $this->string(),
            'card_check_no' => $this->string(),
            'load_no' => $this->string(),
            'driver_name_reported' => $this->string(),
            'purchase_date' => $this->date(),
            'purchase_time' => $this->time(),

            'trip_no' => $this->string(),
            'unit_id' => $this->integer(),
            'driver_id' => $this->integer(),
            'truck_id' => $this->integer(),
            'trailer_id' => $this->integer(),

            'vendor' => $this->string(),
            'city' => $this->string(),
            'state_id' => $this->integer()->notNull(),
            'product_code' => $this->string(),
            'quantity' => $this->decimal(10, 2),
            'cost' => $this->decimal(10, 2),
            'ppg' => $this->decimal(10, 2),

            'description' => $this->string(),
            'err' => $this->string(),
        ]);

        $this->addForeignKey('{{%fuel_import__driver_id_fk}}', '{{%fuel_import}}', 'driver_id', '{{%driver}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%fuel_import__truck_id_fk}}', '{{%fuel_import}}', 'truck_id', '{{%truck}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%fuel_import__trailer_id_fk}}', '{{%fuel_import}}', 'trailer_id', '{{%trailer}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fuel_import__trailer_id_fk}}', '{{%fuel_import}}');
        $this->dropForeignKey('{{%fuel_import__truck_id_fk}}', '{{%fuel_import}}');
        $this->dropForeignKey('{{%fuel_import__driver_id_fk}}', '{{%fuel_import}}');
        $this->dropTable('{{%fuel_import}}');
    }
}
