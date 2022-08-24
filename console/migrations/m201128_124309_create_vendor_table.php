<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vendor}}`.
 */
class m201128_124309_create_vendor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vendor}}', [
            'id' => $this->primaryKey(),
            'notes' => $this->text(),
            'name' => $this->string()->notNull(),
            'address_1' => $this->string()->notNull(),
            'address_2' => $this->string(),
            'city' => $this->string()->notNull(),
            'state_id' => $this->integer(),
            'zip' => $this->string(10)->notNull(),
            'main_phone' => $this->string()->notNull(),
            'main_800' => $this->string(),
            'main_fax' => $this->string(),
            'email' => $this->string(),
            'website' => $this->string(),
            'ar_contact' => $this->string()->notNull(),
            'other_contact' => $this->string(),
            'account_no' => $this->string()->notNull(),
            'mail_list' => $this->boolean()->notNull(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%vendor_state_id_fk}}',
            '{{%vendor}}',
            'state_id',
            '{{%state}}',
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
            '{{%vendor_state_id_fk}}',
            '{{%vendor}}'
        );
        $this->dropTable('{{%vendor}}');
    }
}
