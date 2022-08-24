<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer}}`.
 */
class m201128_115029_create_customer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%customer}}', [
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
            'disp_contact' => $this->string()->notNull(),
            'ap_contact' => $this->string()->notNull(),
            'other_contact' => $this->string(),
            'account_no' => $this->string()->notNull(),
            'federal_id' => $this->string()->notNull(),
            'mc_id' => $this->string()->notNull(),
            'scac' => $this->string()->notNull(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%customer_state_id_fk}}',
            '{{%customer}}',
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
            '{{%customer_state_id_fk}}',
            '{{%customer}}'
        );
        $this->dropTable('{{%customer}}');
    }
}
