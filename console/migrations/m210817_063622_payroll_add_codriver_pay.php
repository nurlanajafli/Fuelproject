<?php

use yii\db\Migration;

/**
 * Class m210817_063622_payroll_add_codriver_pay
 */
class m210817_063622_payroll_add_codriver_pay extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%payroll}}', 'codriver_pay', $this->decimal(10, 2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%payroll}}', 'codriver_pay');
    }
}
