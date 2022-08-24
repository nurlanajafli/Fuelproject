<?php

use common\models\Payroll;
use yii\db\Migration;

/**
 * Class m210827_061940_payroll_fill_d_expense_acct
 */
class m210827_061940_payroll_fill_d_expense_acct extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /** @var Payroll[] $rows */
        $rows = Payroll::find()->joinWith('driver')->all();
        foreach ($rows as $row) {
            if ($row->driver && !$row->d_expense_acct && $row->driver->expense_acct) {
                $row->d_expense_acct = $row->driver->expense_acct;
                $row->save();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Payroll::updateAll(['d_expense_acct' => null]);
    }
}
