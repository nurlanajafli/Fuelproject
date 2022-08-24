<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%driver}}`.
 */
class m201127_161716_create_driver_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%driver}}', [
            'id' => $this->primaryKey(),
            'last_name' => $this->string()->notNull(),
            'first_name' => $this->string()->notNull(),
            'middle_name' => $this->string(),
            'address_1' => $this->string()->notNull(),
            'address_2' => $this->string(),
            'city' => $this->string()->notNull(),
            'state_id' => $this->integer(),
            'zip' => $this->string(10)->notNull(),
            'telephone' => $this->string()->notNull(),
            'cell_phone' => $this->string()->notNull(),
            'other_phone' => $this->string(),
            'office_id' => $this->integer(),
            'web_id' => $this->string(),
            'email_address' => $this->string()->notNull(),
            'user_defined_1' => $this->string(),
            'user_defined_2' => $this->string(),
            'user_defined_3' => $this->string(),
            'social_sec_no' => $this->string()->notNull(),
            'passport_no' => $this->string()->notNull(),
            'passport_exp' => $this->date(),
            'date_of_birth' => $this->date(),
            'hire_date' => $this->date(),
            'mail_list' => $this->boolean()->notNull(),
            'maintenance' => $this->boolean()->notNull(),
            'notes' => $this->text(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%driver_state_id_fk}}',
            '{{%driver}}',
            'state_id',
            '{{%state}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%driver_office_id_fk}}',
            '{{%driver}}',
            'office_id',
            '{{%office}}',
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
            '{{%driver_state_id_fk}}',
            '{{%driver}}'
        );
        $this->dropForeignKey(
            '{{%driver_office_id_fk}}',
            '{{%driver}}'
        );
        $this->dropTable('{{%driver}}');
    }
}
