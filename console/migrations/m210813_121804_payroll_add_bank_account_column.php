<?php

use yii\db\Migration;

/**
 * Class m210813_121804_payroll_add_bank_account_column
 */
class m210813_121804_payroll_add_bank_account_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%payroll}}', 'bank_account', $this->string());
        $this->addColumn('{{%payroll}}', 'pay_to_carrier_id', $this->integer());
        $this->addColumn('{{%payroll}}', 'pay_to_driver_id', $this->integer());
        $this->addColumn('{{%payroll}}', 'pay_to_vendor_id', $this->integer());
        $this->addForeignKey(
            '{{%payroll__bank_account}}',
            '{{%payroll}}',
            'bank_account',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payroll__carrier}}',
            '{{%payroll}}',
            'pay_to_carrier_id',
            '{{%carrier}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payroll__driver}}',
            '{{%payroll}}',
            'pay_to_driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payroll__vendor}}',
            '{{%payroll}}',
            'pay_to_vendor_id',
            '{{%vendor}}',
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
            '{{%payroll__bank_account}}',
            '{{%payroll}}'
        );
        $this->dropForeignKey(
            '{{%payroll__carrier}}',
            '{{%payroll}}'
        );
        $this->dropForeignKey(
            '{{%payroll__driver}}',
            '{{%payroll}}'
        );
        $this->dropForeignKey(
            '{{%payroll__vendor}}',
            '{{%payroll}}'
        );
        $this->dropColumn('{{%payroll}}', 'bank_account');
        $this->dropColumn('{{%payroll}}', 'pay_to_carrier_id');
        $this->dropColumn('{{%payroll}}', 'pay_to_driver_id');
        $this->dropColumn('{{%payroll}}', 'pay_to_vendor_id');
    }
}
