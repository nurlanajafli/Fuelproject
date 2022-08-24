<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%company}}`.
 */
class m201125_151220_create_company_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%company}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'address_1' => $this->string()->notNull(),
            'address_2' => $this->string(),
            'city' => $this->string()->notNull(),
            'state_id' => $this->integer(),
            'zip' => $this->string(10)->notNull(),
            'country' => $this->string()->notNull(),
            'main_phone' => $this->string()->notNull(),
            'main_800' => $this->string(),
            'main_fax' => $this->string(),
            'accounting_phone' => $this->string()->notNull(),
            'ar_contact' => $this->string()->notNull(),
            'ap_contact' => $this->string()->notNull(),
            'business_type' => $this->string()->notNull(),
            'federal_id' => $this->string()->notNull(),
            'dot_id' => $this->string()->notNull(),
            'mc_id' => $this->string()->notNull(),
            'scac' => $this->string()->notNull(),
            'logo' => $this->string()->notNull(),
        ]);
        $this->addForeignKey(
            '{{%company_state_id_fk}}',
            '{{%company}}',
            'state_id',
            '{{%state}}',
            'id',
            'RESTRICT',
            'RESTRICT',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%company_state_id_fk}}', '{{%company}}');
        $this->dropTable('{{%company}}');
    }
}
