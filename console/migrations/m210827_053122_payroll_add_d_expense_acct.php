<?php

use yii\db\Migration;

/**
 * Class m210827_053122_payroll_add_d_expense_acct
 */
class m210827_053122_payroll_add_d_expense_acct extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%payroll}}', 'd_expense_acct', $this->string());
        $this->addForeignKey(
            '{{%payroll__d_expense_acct}}',
            '{{%payroll}}',
            'd_expense_acct',
            '{{%account}}',
            'account',
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
            '{{%payroll__d_expense_acct}}',
            '{{%payroll}}'
        );
        $this->dropColumn('{{%payroll}}', 'd_expense_acct');
    }
}
