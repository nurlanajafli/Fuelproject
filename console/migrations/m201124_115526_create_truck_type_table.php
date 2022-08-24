<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%truck_type}}`.
 */
class m201124_115526_create_truck_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%truck_type}}', [
            'type' => $this->string()->notNull(),
            'description' => $this->string(),
        ]);
        $this->addPrimaryKey('{{%truck_type_type_pk}}', '{{%truck_type}}', 'type');
        $this->batchInsert('{{%truck_type}}', ['type', 'description'], [
            ['B1', 'Box Truck 16'],
            ['B2', 'Box Truck 24'],
            ['CD', 'Conventional Day Cab'],
            ['CS', 'Conventional Sleeper'],
            ['FP', 'Flatbed Pickup'],
            ['S1', 'Stake Bed 16'],
            ['S2', 'Stake Bed 24'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('{{%truck_type_type_pk}}', '{{%truck_type}}');
        $this->dropTable('{{%truck_type}}');
    }
}
