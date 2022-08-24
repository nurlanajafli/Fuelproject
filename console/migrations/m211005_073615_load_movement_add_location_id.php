<?php

use yii\db\Migration;

/**
 * Class m211005_073615_load_movement_add_location_id
 */
class m211005_073615_load_movement_add_location_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%load_movement}}', 'location_id', $this->integer());
        $this->addColumn('{{%load_movement}}', 'truck_id', $this->integer());
        $this->addColumn('{{%load_movement}}', 'trailer_id', $this->integer());
        $this->addColumn('{{%load_movement}}', 'driver_id', $this->integer());
        $this->addForeignKey(
            '{{%load_movement__location}}',
            '{{%load_movement}}',
            'location_id',
            '{{%location}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_movement__truck}}',
            '{{%load_movement}}',
            'truck_id',
            '{{%truck}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_movement__trailer}}',
            '{{%load_movement}}',
            'trailer_id',
            '{{%trailer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_movement__driver}}',
            '{{%load_movement}}',
            'driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%load_movement__location}}',
            '{{%load_movement}}'
        );
        $this->dropForeignKey(
            '{{%load_movement__truck}}',
            '{{%load_movement}}'
        );
        $this->dropForeignKey(
            '{{%load_movement__trailer}}',
            '{{%load_movement}}'
        );
        $this->dropForeignKey(
            '{{%load_movement__driver}}',
            '{{%load_movement}}'
        );
        $this->dropColumn('{{%load_movement}}', 'location_id');
        $this->dropColumn('{{%load_movement}}', 'truck_id');
        $this->dropColumn('{{%load_movement}}', 'trailer_id');
        $this->dropColumn('{{%load_movement}}', 'driver_id');
    }
}
