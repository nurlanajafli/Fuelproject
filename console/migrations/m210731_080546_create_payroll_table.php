<?php

use yii\db\Migration;

class m210731_080546_create_payroll_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payroll}}', [
            'id' => $this->primaryKey(),
            'payroll_batch_id' => $this->integer()->notNull(),
            'driver_id' => $this->integer(),
            'driver_type' => $this->string(),
            'office_id' => $this->integer(),
            'owner' => $this->string(), // TODO: TBD
            'direct_deposit' => $this->string(), // TODO: TBD (abbreviation is DD)
            'cd' => $this->string(), // TODO: TBD
        ]);
        $this->addForeignKey(
            '{{%payroll_payroll_batch_id_fk}}',
            '{{%payroll}}',
            'payroll_batch_id',
            '{{%payroll_batch}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payroll_driver_id_fk}}',
            '{{%payroll}}',
            'driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payroll_office_id_fk}}',
            '{{%payroll}}',
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
            '{{%payroll_payroll_batch_id_fk}}',
            '{{%payroll}}'
        );
        $this->dropForeignKey(
            '{{%payroll_driver_id_fk}}',
            '{{%payroll}}'
        );
        $this->dropForeignKey(
            '{{%payroll_office_id_fk}}',
            '{{%payroll}}'
        );
        $this->dropTable('{{%payroll}}');
    }
}
