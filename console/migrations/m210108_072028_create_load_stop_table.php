<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%load_stop}}`.
 */
class m210108_072028_create_load_stop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%load_stop}}', [
            'id' => $this->primaryKey(),
            'load_id' => $this->integer()->notNull(),
            'stop_number' => $this->integer()->notNull(),
            'stop_type' => $this->string(),
            'available_from' => $this->date(),
            'available_thru' => $this->date(),
            'time_from' => $this->time(),
            'time_to' => $this->time(),
            'company_id' => $this->integer(),
            'address' => $this->string(),
            'city' => $this->string(),
            'state_id' => $this->integer(),
            'zip' => $this->string(10),
            'zone' => $this->string(),
            'phone' => $this->string(),
            'contact' => $this->string(),
            'reference' => $this->string(),
            'appt_required' => $this->boolean()->notNull(),
            'appt_reference' => $this->string(),
            'notes' => $this->text(),
            'directions' => $this->text(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%load_stop_load_id_fk}}',
            '{{%load_stop}}',
            'load_id',
            '{{%load}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_stop_company_id_fk}}',
            '{{%load_stop}}',
            'company_id',
            '{{%location}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_stop_state_id_fk}}',
            '{{%load_stop}}',
            'state_id',
            '{{%state}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_stop_zone_fk}}',
            '{{%load_stop}}',
            'zone',
            '{{%zone}}',
            'code',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_stop_created_by_fk}}',
            '{{%load_stop}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_stop_updated_by_fk}}',
            '{{%load_stop}}',
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
            '{{%load_stop_load_id_fk}}',
            '{{%load_stop}}'
        );
        $this->dropForeignKey(
            '{{%load_stop_company_id_fk}}',
            '{{%load_stop}}'
        );
        $this->dropForeignKey(
            '{{%load_stop_state_id_fk}}',
            '{{%load_stop}}'
        );
        $this->dropForeignKey(
            '{{%load_stop_zone_fk}}',
            '{{%load_stop}}'
        );
        $this->dropForeignKey(
            '{{%load_stop_created_by_fk}}',
            '{{%load_stop}}'
        );
        $this->dropForeignKey(
            '{{%load_stop_updated_by_fk}}',
            '{{%load_stop}}'
        );
        $this->dropTable('{{%load_stop}}');
    }
}
