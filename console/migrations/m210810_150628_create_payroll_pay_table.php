<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payroll_pay}}`.
 */
class m210810_150628_create_payroll_pay_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payroll_pay}}', [
            'id' => $this->primaryKey(),
            'payroll_id' => $this->integer()->notNull(),
            'd_load_id' => $this->integer(),
            'created_at' => $this->timestamp()->notNull(),
            'created_by' => $this->integer()
        ]);
        $this->addForeignKey(
            '{{%payroll_pay__payroll}}',
            '{{%payroll_pay}}',
            'payroll_id',
            '{{%payroll}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payroll_pay__d_load}}',
            '{{%payroll_pay}}',
            'd_load_id',
            '{{%load}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payroll_pay__creator}}',
            '{{%payroll_pay}}',
            'created_by',
            '{{%user}}',
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
            '{{%payroll_pay__payroll}}',
            '{{%payroll_pay}}'
        );
        $this->dropForeignKey(
            '{{%payroll_pay__d_load}}',
            '{{%payroll_pay}}'
        );
        $this->dropForeignKey(
            '{{%payroll_pay__creator}}',
            '{{%payroll_pay}}'
        );
        $this->dropTable('{{%payroll_pay}}');
    }
}
