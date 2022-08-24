<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%load_drop}}`.
 */
class m210923_091425_create_load_drop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%load_drop}}', [
            'id' => $this->primaryKey(),
            'load_id' => $this->integer()->notNull(),
            'location_id' => $this->integer()->notNull(),
            'drop_truck_id' => $this->integer()->notNull(),
            'drop_trailer_id' => $this->integer(),
            'drop_trailer_2_id' => $this->integer(),
            'drop_driver_id' => $this->integer()->notNull(),
            'drop_codriver_id' => $this->integer(),
            'drop_unit_id' => $this->integer()->notNull(),
            'drop_date' => $this->date()->notNull(),
            'drop_time_from' => $this->time()->notNull(),
            'drop_time_to' => $this->time(),
            'retrieve_date' => $this->date(),
            'retrieve_time' => $this->time(),
            'trailer_disposition' => $this->string()->notNull(),
            'retrieve_truck_id' => $this->integer(),
            'retrieve_trailer_id' => $this->integer(),
            'retrieve_trailer_2_id' => $this->integer(),
            'retrieve_driver_id' => $this->integer(),
            'retrieve_codriver_id' => $this->integer(),
            'retrieve_unit_id' => $this->integer(),
            'created_at' => $this->timestamp()->notNull(),
            'created_by' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            '{{%load_drop__load}}',
            '{{%load_drop}}',
            'load_id',
            '{{%load}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%load_drop__location}}',
            '{{%load_drop}}',
            'location_id',
            '{{%location}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%load_drop__dtruck}}',
            '{{%load_drop}}',
            'drop_truck_id',
            '{{%truck}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%load_drop__dtrailer}}',
            '{{%load_drop}}',
            'drop_trailer_id',
            '{{%trailer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%load_drop__dtrailer2}}',
            '{{%load_drop}}',
            'drop_trailer_2_id',
            '{{%trailer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%load_drop__ddriver}}',
            '{{%load_drop}}',
            'drop_driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%load_drop__dcodriver}}',
            '{{%load_drop}}',
            'drop_codriver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%load_drop__dunit}}',
            '{{%load_drop}}',
            'drop_unit_id',
            '{{%unit}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%load_drop__rtruck}}',
            '{{%load_drop}}',
            'retrieve_truck_id',
            '{{%truck}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%load_drop__rtrailer}}',
            '{{%load_drop}}',
            'retrieve_trailer_id',
            '{{%trailer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%load_drop__rtrailer2}}',
            '{{%load_drop}}',
            'retrieve_trailer_2_id',
            '{{%trailer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%load_drop__rdriver}}',
            '{{%load_drop}}',
            'retrieve_driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%load_drop__rcodriver}}',
            '{{%load_drop}}',
            'retrieve_codriver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%load_drop__runit}}',
            '{{%load_drop}}',
            'retrieve_unit_id',
            '{{%unit}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%load_drop__creator}}',
            '{{%load_drop}}',
            'created_by',
            '{{%user}}',
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
            '{{%load_drop__load}}',
            '{{%load_drop}}'
        );

        $this->dropForeignKey(
            '{{%load_drop__location}}',
            '{{%load_drop}}'
        );

        $this->dropForeignKey(
            '{{%load_drop__dtruck}}',
            '{{%load_drop}}'
        );

        $this->dropForeignKey(
            '{{%load_drop__dtrailer}}',
            '{{%load_drop}}'
        );

        $this->dropForeignKey(
            '{{%load_drop__dtrailer2}}',
            '{{%load_drop}}'
        );

        $this->dropForeignKey(
            '{{%load_drop__ddriver}}',
            '{{%load_drop}}'
        );

        $this->dropForeignKey(
            '{{%load_drop__dcodriver}}',
            '{{%load_drop}}'
        );

        $this->dropForeignKey(
            '{{%load_drop__dunit}}',
            '{{%load_drop}}'
        );

        $this->dropForeignKey(
            '{{%load_drop__rtruck}}',
            '{{%load_drop}}'
        );

        $this->dropForeignKey(
            '{{%load_drop__rtrailer}}',
            '{{%load_drop}}'
        );

        $this->dropForeignKey(
            '{{%load_drop__rtrailer2}}',
            '{{%load_drop}}'
        );

        $this->dropForeignKey(
            '{{%load_drop__rdriver}}',
            '{{%load_drop}}'
        );

        $this->dropForeignKey(
            '{{%load_drop__rcodriver}}',
            '{{%load_drop}}'
        );

        $this->dropForeignKey(
            '{{%load_drop__runit}}',
            '{{%load_drop}}'
        );

        $this->dropForeignKey(
            '{{%load_drop__creator}}',
            '{{%load_drop}}'
        );

        $this->dropTable('{{%load_drop}}');
    }
}
