<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%load_movement}}`.
 */
class m210421_135427_create_load_movement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%load_movement}}', [
            'id' => $this->primaryKey(),
            'action' => $this->string()->notNull(),
            'unit_id' => $this->integer()->notNull(),
            'load_id' => $this->integer()->notNull(),
            'load_stop_id' => $this->integer()->unique(),
            'seal_no' => $this->string(),
            'commodity_weight' => $this->integer(),
            'commodity_pieces' => $this->decimal(),
            'bol' => $this->string(),
            'arrived_date' => $this->date(),
            'arrived_time_in' => $this->time(),
            'arrived_time_out' => $this->time(),
            'created_at' => $this->timestamp()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->timestamp(),
            'updated_by' => $this->integer(),
        ]);
        $this->addForeignKey(
            '{{%load_movement_unit_id_fk}}',
            '{{%load_movement}}',
            'unit_id',
            '{{%unit}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_movement_load_id_fk}}',
            '{{%load_movement}}',
            'load_id',
            '{{%load}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_movement_load_stop_id_fk}}',
            '{{%load_movement}}',
            'load_stop_id',
            '{{%load_stop}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_movement_created_by_fk}}',
            '{{%load_movement}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_movement_updated_by_fk}}',
            '{{%load_movement}}',
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
            '{{%load_movement_unit_id_fk}}',
            '{{%load_movement}}'
        );
        $this->dropForeignKey(
            '{{%load_movement_load_id_fk}}',
            '{{%load_movement}}'
        );
        $this->dropForeignKey(
            '{{%load_movement_load_stop_id_fk}}',
            '{{%load_movement}}'
        );
        $this->dropForeignKey(
            '{{%load_movement_created_by_fk}}',
            '{{%load_movement}}'
        );
        $this->dropForeignKey(
            '{{%load_movement_updated_by_fk}}',
            '{{%load_movement}}'
        );
        $this->dropTable('{{%load_movement}}');
    }
}
