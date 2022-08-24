<?php

use yii\db\Migration;

/**
 * Class m210812_080502_payroll_add_outcome_columns
 */
class m210812_080502_payroll_add_outcome_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%payroll}}', 'dispatch_pay', $this->decimal(10, 2)->notNull()->defaultValue(0));
        $this->addColumn('{{%payroll}}', 'mileage_pay', $this->decimal(10, 2)->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%payroll}}', 'dispatch_pay');
        $this->dropColumn('{{%payroll}}', 'mileage_pay');
    }
}
