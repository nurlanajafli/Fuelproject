<?php

use yii\db\Migration;

/**
 * Class m210804_125536_payroll_adj_code_add_default_amount_values
 */
class m210804_125536_payroll_adj_code_add_default_amount_values extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $values = [
            "13 Cents Per Mile"     => 0,
            "Administrative Fees"   => 35,
            "Deductibles"           => 0,
            "IFTA"                  => 35,
            "Insurance Lease"       => 450,
            "Insurance OCCAC"       => 35,
            "Insurance Owner"       => 350,
            "Loan Deduction"        => 0,
            "loc_tax_tst"           => 0,
            "Other"                 => 0,
            "Toll"                  => 0,
            "Trailer Fee"           => 125,
            "Truck Lease"           => 730
        ];

        foreach ($values as $key => $value) {
            $model = \common\models\PayrollAdjustmentCode::findOne(['code' => $key]);
            if (!$model) {
                continue;
            }
            /** @var \common\models\PayrollAdjustmentCode $model */
            $model->amount = $value;
            $model->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
