<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vendor_contact_note}}`.
 */
class m201216_120051_create_vendor_contact_note_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vendor_contact_note}}', [
            'id' => $this->primaryKey(),
            'vendor_id' => $this->integer()->notNull(),
            'contact' => $this->string()->notNull(),
            'code' => $this->string(),
            'notes' => $this->text(),
            'next_contact' => $this->timestamp(),
            'post_reminder' => $this->boolean()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP')
        ]);
        $this->addForeignKey(
            '{{%vendor_contact_note_vendor_id_fk}}',
            '{{%vendor_contact_note}}',
            'vendor_id',
            '{{%vendor}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%vendor_contact_note_code_fk}}',
            '{{%vendor_contact_note}}',
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
            '{{%vendor_contact_note_code_fk}}',
            '{{%vendor_contact_note}}'
        );
        $this->dropForeignKey(
            '{{%vendor_contact_note_vendor_id_fk}}',
            '{{%vendor_contact_note}}'
        );
        $this->dropTable('{{%vendor_contact_note}}');
    }
}
