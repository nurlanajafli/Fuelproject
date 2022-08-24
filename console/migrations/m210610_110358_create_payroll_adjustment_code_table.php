<?php

use common\enums\PayrollAdjustmentClass;
use common\enums\PayrollAdjustmentType;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%payroll_adjustment_code}}`.
 */
class m210610_110358_create_payroll_adjustment_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payroll_adjustment_code}}', [
            'code' => $this->string()->notNull(),
            "adj_type" => $this->string()->notNull(),
            "post_to_carrier_id" => $this->integer(),
            "post_to_customer_id" => $this->integer(),
            "post_to_vendor_id" => $this->integer(),
            "adj_class" => $this->string(),
            "account" => $this->string(4)->notNull(),
            "based_on" => $this->string(), // Mileage, for NON_TAX only
            "percent" => $this->decimal(10, 4),
            "amount" => $this->decimal(10, 2),
            "empr_paid" => $this->boolean()->defaultValue(false),
            "inactive" => $this->boolean()->defaultValue(false),
        ]);

        $this->addPrimaryKey('{{%payroll_adjustment_code_pk}}', '{{%payroll_adjustment_code}}', 'code');

        $this->addForeignKey(
            '{{%payroll_adjustment_code_account_fk}}',
            '{{%payroll_adjustment_code}}',
            'account',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );

        $this->batchInsert(
            '{{%payroll_adjustment_code}}',
            ['code', 'account', 'adj_type', 'adj_class'],
            [
                ['13 Cents Per Mile',   '3025', PayrollAdjustmentType::NON_TAX_ADJUSTMENT, PayrollAdjustmentClass::NON_TAX],
                ['Administrative Fees', '3001', PayrollAdjustmentType::NON_TAX_ADJUSTMENT, PayrollAdjustmentClass::NON_TAX],
                ['Deductibles',         '1056', PayrollAdjustmentType::NON_TAX_ADJUSTMENT, PayrollAdjustmentClass::NON_TAX],
                ['IFTA',                '5400', PayrollAdjustmentType::NON_TAX_ADJUSTMENT, PayrollAdjustmentClass::NON_TAX],
                ['Insurance Lease',     '4100', PayrollAdjustmentType::NON_TAX_ADJUSTMENT, PayrollAdjustmentClass::NON_TAX],
                ['Insurance OCCAC',     '4100', PayrollAdjustmentType::NON_TAX_ADJUSTMENT, PayrollAdjustmentClass::NON_TAX],
                ['Insurance Owner',     '4100', PayrollAdjustmentType::NON_TAX_ADJUSTMENT, PayrollAdjustmentClass::NON_TAX],
                ['Loan Deduction',      '1055', PayrollAdjustmentType::NON_TAX_ADJUSTMENT, PayrollAdjustmentClass::NON_TAX],
                ['Other',               '5406', PayrollAdjustmentType::NON_TAX_ADJUSTMENT, PayrollAdjustmentClass::NON_TAX],
                ['Toll',                '5405', PayrollAdjustmentType::NON_TAX_ADJUSTMENT, PayrollAdjustmentClass::NON_TAX],
                ['Trailer Fee',         '3030', PayrollAdjustmentType::NON_TAX_ADJUSTMENT, PayrollAdjustmentClass::NON_TAX],
                ['Truck Lease',         '3020', PayrollAdjustmentType::NON_TAX_ADJUSTMENT, PayrollAdjustmentClass::NON_TAX],
            ]
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payroll_adjustment_code}}');
    }
}
