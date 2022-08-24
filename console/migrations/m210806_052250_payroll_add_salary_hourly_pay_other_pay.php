<?php

use yii\db\Migration;

/**
 * Class m210806_052250_payroll_add_salary_hourly_pay_other_pay
 */
class m210806_052250_payroll_add_salary_hourly_pay_other_pay extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = '{{%payroll}}';
        $this->addColumn($table, 'salary_amount', $this->decimal(10, 2));
        $this->addColumn($table, 'total_hours', $this->decimal(10, 2));
        $this->addColumn($table, 'ot_hours', $this->decimal(10, 2));
        $this->addColumn($table, 'ot_2_hours', $this->decimal(10, 2));
        $this->addColumn($table, 'st_rate', $this->decimal(10, 2));
        $this->addColumn($table, 'ot_rate', $this->decimal(10, 2));
        $this->addColumn($table, 'ot_2_rate', $this->decimal(10, 2));
        $this->addColumn($table, 'description', $this->string());
        $this->addColumn($table, 'other_pay_amount', $this->decimal(10, 2));
        $this->addColumn($table, 'posted', $this->boolean()->notNull()->defaultValue(false));
        $this->dropColumn('{{%payroll_batch}}', 'posted');
        $this->dropColumn('{{%payroll_batch}}', 'unposted');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = '{{%payroll}}';
        $this->dropColumn($table, 'salary_amount');
        $this->dropColumn($table, 'total_hours');
        $this->dropColumn($table, 'ot_hours');
        $this->dropColumn($table, 'ot_2_hours');
        $this->dropColumn($table, 'st_rate');
        $this->dropColumn($table, 'ot_rate');
        $this->dropColumn($table, 'ot_2_rate');
        $this->dropColumn($table, 'description');
        $this->dropColumn($table, 'other_pay_amount');
        $this->dropColumn($table, 'posted');
        $this->addColumn('{{%payroll_batch}}', 'posted', $this->integer());
        $this->addColumn('{{%payroll_batch}}', 'unposted', $this->integer());
    }
}
