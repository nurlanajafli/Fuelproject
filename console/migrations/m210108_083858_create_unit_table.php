<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%unit}}`.
 */
class m210108_083858_create_unit_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%unit}}', [
            'id' => $this->primaryKey(),
            'active' => $this->date(),
            'driver_id' => $this->integer(),
            'co_driver_id' => $this->integer(),
            'truck_id' => $this->integer(),
            'trailer_id' => $this->integer(),
            'trailer_2_id' => $this->integer(),
            'office_id' => $this->integer(),
            'updated_by' => $this->integer()->notNull(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%unit_driver_id_fk}}',
            '{{%unit}}',
            'driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%unit_co_driver_id_fk}}',
            '{{%unit}}',
            'co_driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%unit_truck_id_fk}}',
            '{{%unit}}',
            'truck_id',
            '{{%truck}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%unit_trailer_id_fk}}',
            '{{%unit}}',
            'trailer_id',
            '{{%trailer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%unit_trailer_2_id_fk}}',
            '{{%unit}}',
            'trailer_2_id',
            '{{%trailer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%unit_office_id_fk}}',
            '{{%unit}}',
            'office_id',
            '{{%office}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%unit_created_by_fk}}',
            '{{%unit}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%unit_updated_by_fk}}',
            '{{%unit}}',
            'updated_by',
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
            '{{%unit_driver_id_fk}}',
            '{{%unit}}'
        );
        $this->dropForeignKey(
            '{{%unit_co_driver_id_fk}}',
            '{{%unit}}'
        );
        $this->dropForeignKey(
            '{{%unit_truck_id_fk}}',
            '{{%unit}}'
        );
        $this->dropForeignKey(
            '{{%unit_trailer_id_fk}}',
            '{{%unit}}'
        );
        $this->dropForeignKey(
            '{{%unit_trailer_2_id_fk}}',
            '{{%unit}}'
        );
        $this->dropForeignKey(
            '{{%unit_office_id_fk}}',
            '{{%unit}}'
        );
        $this->dropForeignKey(
            '{{%unit_created_by_fk}}',
            '{{%unit}}'
        );
        $this->dropForeignKey(
            '{{%unit_updated_by_fk}}',
            '{{%unit}}'
        );
        $this->dropTable('{{%unit}}');
    }
}
