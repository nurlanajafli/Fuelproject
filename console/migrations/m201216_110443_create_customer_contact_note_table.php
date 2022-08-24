<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer_contact_note}}`.
 */
class m201216_110443_create_customer_contact_note_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%company_note_code}}', [
            'code' => $this->string()->notNull(),
            'description' => $this->string()
        ]);
        $this->addPrimaryKey(
            '{{%company_note_code_code_pk}}',
            '{{%company_note_code}}',
            'code'
        );
        $this->batchInsert('{{%company_note_code}}', ['code'], [['SECURITY'], ['WATCHMAN'], ['ACCOUNTING']]);
        $this->createTable('{{%customer_contact_note}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer()->notNull(),
            'contact' => $this->string()->notNull(),
            'code' => $this->string(),
            'notes' => $this->text(),
            'next_contact' => $this->timestamp(),
            'post_reminder' => $this->boolean()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP')
        ]);
        $this->addForeignKey(
            '{{%customer_contact_note_customer_id_fk}}',
            '{{%customer_contact_note}}',
            'customer_id',
            '{{%customer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%customer_contact_note_code_fk}}',
            '{{%customer_contact_note}}',
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
            '{{%customer_contact_note_code_fk}}',
            '{{%customer_contact_note}}'
        );
        $this->dropForeignKey(
            '{{%customer_contact_note_customer_id_fk}}',
            '{{%customer_contact_note}}'
        );
        $this->dropTable('{{%customer_contact_note}}');
        $this->dropPrimaryKey(
            '{{%company_note_code_code_pk}}',
            '{{%company_note_code}}',
        );
        $this->dropTable('{{%company_note_code}}');
    }
}
