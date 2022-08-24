<?php

use yii\db\Migration;
use common\enums\PayrollAdjustmentType;
use common\enums\PayrollAdjustmentClass;

/**
 * Class m210721_051240_alter_payroll_adjustment_code_post_to_columns
 */
class m210721_051240_alter_payroll_adjustment_code_post_to_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('{{%payroll_adjustment_code_post_to_customer_fk}}', '{{%payroll_adjustment_code}}');
        $this->dropColumn('{{%payroll_adjustment_code}}', 'post_to_customer_id');
        $this->addColumn('{{%payroll_adjustment_code}}', 'post_to_driver_id', $this->integer());
        $this->addForeignKey(
            '{{%payroll_adjustment_code_post_to_driver_fk}}',
            '{{%payroll_adjustment_code}}',
            'post_to_driver_id',
            '{{%driver}}',
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
        $this->dropForeignKey('{{%payroll_adjustment_code_post_to_driver_fk}}', '{{%payroll_adjustment_code}}');
        $this->dropColumn('{{%payroll_adjustment_code}}', 'post_to_driver_id');
        $this->addColumn('{{%payroll_adjustment_code}}', 'post_to_customer_id', $this->integer());
        $this->addForeignKey(
            '{{%payroll_adjustment_code_post_to_customer_fk}}',
            '{{%payroll_adjustment_code}}',
            'post_to_customer_id',
            '{{%customer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }
}
