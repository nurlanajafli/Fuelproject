<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%load_note}}`.
 */
class m210108_063552_create_load_note_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%load_note_type}}', [
            'id' => $this->primaryKey(),
            'description' => $this->string()->notNull()
        ]);
        $this->batchInsert('{{%load_note_type}}', ['description'], [
            ['Assigned'],
            ['Confirmed'],
            ['At Origin'],
            ['Loading'],
            ['Moving'],
            ['At Destination'],
            ['Unloading'],
            ['Dropped'],
            ['Hold'],
            ['Rejected'],
            ['Customs Hold'],
            ['Customs Released'],
            ['Out For Delivery'],
        ]);
        $this->createTable('{{%load_note}}', [
            'id' => $this->primaryKey(),
            'load_id' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
            'last_action' => $this->integer()->notNull(),
            'notes' => $this->text()->notNull()
        ]);
        $this->addForeignKey(
            '{{%load_note_load_id_fk}}',
            '{{%load_note}}',
            'load_id',
            '{{%load}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_note_created_by_fk}}',
            '{{%load_note}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_note_last_action_fk}}',
            '{{%load_note}}',
            'last_action',
            '{{%load_note_type}}',
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
            '{{%load_note_load_id_fk}}',
            '{{%load_note}}'
        );
        $this->dropForeignKey(
            '{{%load_note_created_by_fk}}',
            '{{%load_note}}'
        );
        $this->dropForeignKey(
            '{{%load_note_last_action_fk}}',
            '{{%load_note}}'
        );
        $this->dropTable('{{%load_note}}');
        $this->dropTable('{{%load_note_type}}');
    }
}
