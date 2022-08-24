<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%carrier_contact_note}}`.
 */
class m201216_121130_create_carrier_contact_note_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%carrier_contact_note}}', [
            'id' => $this->primaryKey(),
            'carrier_id' => $this->integer()->notNull(),
            'contact' => $this->string()->notNull(),
            'code' => $this->string(),
            'notes' => $this->text(),
            'next_contact' => $this->timestamp(),
            'post_reminder' => $this->boolean()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP')
        ]);
        $this->addForeignKey(
            '{{%carrier_contact_note_carrier_id_fk}}',
            '{{%carrier_contact_note}}',
            'carrier_id',
            '{{%carrier}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%carrier_contact_note_code_fk}}',
            '{{%carrier_contact_note}}',
            'code',
            '{{%company_note_code}}',
            'code',
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
            '{{%carrier_contact_note_code_fk}}',
            '{{%carrier_contact_note}}'
        );
        $this->dropForeignKey(
            '{{%carrier_contact_note_carrier_id_fk}}',
            '{{%carrier_contact_note}}'
        );
        $this->dropTable('{{%carrier_contact_note}}');
    }
}
