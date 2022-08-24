<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%carrier}}`.
 */
class m201128_131711_create_carrier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%carrier}}', [
            'id' => $this->primaryKey(),
            'notes' => $this->text(),
            'special_instructions' => $this->text(),
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
            'disp_contact' => $this->string()->notNull(),
            'ar_contact' => $this->string()->notNull(),
            'other_contact' => $this->string(),
            'account_no' => $this->string()->notNull(),
            'federal_id' => $this->string()->notNull(),
            'mail_list' => $this->boolean()->notNull(),
        ]);
        $this->addForeignKey(
            '{{%carrier_state_id_fk}}',
            '{{%carrier}}',
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
            '{{%carrier_state_id_fk}}',
            '{{%carrier}}'
        );
        $this->dropTable('{{%carrier}}');
    }
}
