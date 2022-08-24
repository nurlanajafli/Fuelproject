<?php

use yii\db\Migration;

/**
 * Class m210119_080416_driver_add_pay_defaults_columns
 */
class m210119_080416_driver_add_pay_defaults_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $baseTable = 'driver';
        $this->addColumn("{{%$baseTable}}", 'type', $this->string());
        $tables = ['vendor', $baseTable, 'carrier'];
        foreach ($tables as $table) {
            $this->addColumn("{{%$baseTable}}", $column = "pay_to_{$table}_id", $this->integer());
            $this->addForeignKey(
                "{{%{$baseTable}_{$column}_fk}}",
                "{{%$baseTable}}",
                $column,
                "{{%$table}}",
                'id',
                'RESTRICT',
                'CASCADE'
            );
        }
        $this->addColumn("{{%$baseTable}}", $column = 'expense_acct', $this->string());
        $this->addForeignKey(
            "{{%{$baseTable}_{$column}_fk}}",
            "{{%$baseTable}}",
            $column,
            "{{%account}}",
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addColumn("{{%$baseTable}}", $column = 'bank_acct', $this->string());
        $this->addForeignKey(
            "{{%{$baseTable}_{$column}_fk}}",
            "{{%$baseTable}}",
            $column,
            "{{%account}}",
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addColumn("{{%$baseTable}}", $column = 'co_driver_id', $this->integer());
        $this->addForeignKey(
            "{{%{$baseTable}_{$column}_fk}}",
            "{{%$baseTable}}",
            $column,
            "{{%$baseTable}}",
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addColumn("{{%$baseTable}}", 'pay_standard', $this->string());
        $this->addColumn("{{%$baseTable}}", 'period_salary', $this->decimal());
        $this->addColumn("{{%$baseTable}}", 'hourly_rate', $this->decimal());
        $this->addColumn("{{%$baseTable}}", 'addl_ot_pay', $this->decimal());
        $this->addColumn("{{%$baseTable}}", 'addl_ot_pay_2', $this->decimal());
        $this->addColumn("{{%$baseTable}}", 'base_hours', $this->decimal());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $baseTable = 'driver';
        $this->dropColumn("{{%$baseTable}}", 'type');
        $tables = ['vendor', $baseTable, 'carrier'];
        foreach ($tables as $table) {
            $column = "pay_to_{$table}_id";
            $this->dropForeignKey(
                "{{%{$baseTable}_{$column}_fk}}",
                "{{%$baseTable}}"
            );
            $this->dropColumn("{{%$baseTable}}", "pay_to_{$table}_id");
        }
        $column = 'expense_acct';
        $this->dropForeignKey(
            "{{%{$baseTable}_{$column}_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropColumn("{{%$baseTable}}", $column);

        $column = 'bank_acct';
        $this->dropForeignKey(
            "{{%{$baseTable}_{$column}_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropColumn("{{%$baseTable}}", $column);

        $column = 'co_driver_id';
        $this->dropForeignKey(
            "{{%{$baseTable}_{$column}_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropColumn("{{%$baseTable}}", $column);

        $this->dropColumn("{{%$baseTable}}", 'pay_standard');
        $this->dropColumn("{{%$baseTable}}", 'period_salary');
        $this->dropColumn("{{%$baseTable}}", 'hourly_rate');
        $this->dropColumn("{{%$baseTable}}", 'addl_ot_pay');
        $this->dropColumn("{{%$baseTable}}", 'addl_ot_pay_2');
        $this->dropColumn("{{%$baseTable}}", 'base_hours');
    }
}
