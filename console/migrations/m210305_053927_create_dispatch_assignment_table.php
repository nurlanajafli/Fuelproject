<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dispatch_assignment}}`.
 */
class m210305_053927_create_dispatch_assignment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dispatch_assignment}}', [
            'id' => $this->primaryKey(),
            'load_id' => $this->integer()->notNull()->unique(),
            'pay_code' => $this->string()->notNull(),
            'date' => $this->date()->notNull(),
            'unit_id' => $this->integer()->notNull(),
            'driver_id' => $this->integer()->notNull(),
            'codriver_id' => $this->integer(),
            'truck_id' => $this->integer()->notNull(),
            'trailer_id' => $this->integer(),
            'trailer2_id' => $this->integer(),
            'notes' => $this->text(),

            'driver_pay_source' => $this->string()->notNull(),
            'driver_pay_matrix' => $this->integer(),
            'driver_pay_type' => $this->string()->notNull(),
            'driver_loaded_miles' => $this->integer(),
            'driver_empty_miles' => $this->integer(),
            'driver_loaded_rate' => $this->decimal(10, 2),
            'driver_empty_rate' => $this->decimal(10, 2),
            'driver_total_pay' => $this->decimal(10, 2),

            'codriver_pay_source' => $this->string(),
            'codriver_pay_matrix' => $this->integer(),
            'codriver_pay_type' => $this->string(),
            'codriver_loaded_miles' => $this->integer(),
            'codriver_empty_miles' => $this->integer(),
            'codriver_loaded_rate' => $this->decimal(10, 2),
            'codriver_empty_rate' => $this->decimal(10, 2),
            'codriver_total_pay' => $this->decimal(10, 2),

            'dispatch_start_date' => $this->date(),
            'dispatch_start_time_in' => $this->time(),
            'dispatch_start_time_out' => $this->time(),

            'dispatch_deliver_date' => $this->date(),
            'dispatch_deliver_time_in' => $this->time(),
            'dispatch_deliver_time_out' => $this->time(),

            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->addForeignKey(
            'dispatch_assignment_load_id_fk',
            '{{%dispatch_assignment}}',
            'load_id',
            '{{%load}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->addForeignKey(
            'dispatch_assignment_unit_id_fk',
            '{{%dispatch_assignment}}',
            'unit_id',
            '{{%unit}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->addForeignKey(
            'dispatch_assignment_driver_id_fk',
            '{{%dispatch_assignment}}',
            'driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->addForeignKey(
            'dispatch_assignment_codriver_id_fk',
            '{{%dispatch_assignment}}',
            'codriver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->addForeignKey(
            'dispatch_assignment_truck_id_fk',
            '{{%dispatch_assignment}}',
            'truck_id',
            '{{%truck}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->addForeignKey(
            'dispatch_assignment_trailer_id_fk',
            '{{%dispatch_assignment}}',
            'trailer_id',
            '{{%trailer}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->addForeignKey(
            'dispatch_assignment_trailer2_id_fk',
            '{{%dispatch_assignment}}',
            'trailer2_id',
            '{{%trailer}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dispatch_assignment}}');
    }
}
