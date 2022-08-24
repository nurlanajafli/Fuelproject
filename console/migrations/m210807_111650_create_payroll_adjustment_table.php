<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payroll_adjustment}}`.
 */
class m210807_111650_create_payroll_adjustment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payroll_adjustment}}', [
            'id' => $this->primaryKey(),
            'payroll_id' => $this->integer(),
            'd_payroll_adjustment_code' => $this->string(),
            'd_load_id' => $this->integer(),
            'd_description' => $this->string(),
            'd_percent' => $this->decimal(6, 2),
            'd_amount' => $this->decimal(10, 2),
            'd_charge' => $this->string(),
            'd_post_to_carrier_id' => $this->integer(),
            'd_post_to_driver_id' => $this->integer(),
            'd_post_to_vendor_id' => $this->integer(),
            'd_account' => $this->string(),
            'd_calc_by' => $this->string(),
            'created_at' => $this->timestamp()->notNull(),
            'created_by' => $this->integer(),
            'updated_at' => $this->timestamp(),
            'updated_by' => $this->integer(),
        ]);
        $this->addForeignKey(
            '{{%payroll_adjustment__payroll}}',
            '{{%payroll_adjustment}}',
            'payroll_id',
            '{{%payroll}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payroll_adjustment__d_code}}',
            '{{%payroll_adjustment}}',
            'd_payroll_adjustment_code',
            '{{%payroll_adjustment_code}}',
            'code',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payroll_adjustment__d_load}}',
            '{{%payroll_adjustment}}',
            'd_load_id',
            '{{%load}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payroll_adjustment__d_carrier}}',
            '{{%payroll_adjustment}}',
            'd_post_to_carrier_id',
            '{{%carrier}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payroll_adjustment__d_driver}}',
            '{{%payroll_adjustment}}',
            'd_post_to_driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payroll_adjustment__d_vendor}}',
            '{{%payroll_adjustment}}',
            'd_post_to_vendor_id',
            '{{%vendor}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payroll_adjustment__d_account}}',
            '{{%payroll_adjustment}}',
            'd_account',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payroll_adjustment__creator}}',
            '{{%payroll_adjustment}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payroll_adjustment__updater}}',
            '{{%payroll_adjustment}}',
            'updated_by',
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
            '{{%payroll_adjustment__payroll}}',
            '{{%payroll_adjustment}}'
        );
        $this->dropForeignKey(
            '{{%payroll_adjustment__d_code}}',
            '{{%payroll_adjustment}}'
        );
        $this->dropForeignKey(
            '{{%payroll_adjustment__d_load}}',
            '{{%payroll_adjustment}}'
        );
        $this->dropForeignKey(
            '{{%payroll_adjustment__d_carrier}}',
            '{{%payroll_adjustment}}'
        );
        $this->dropForeignKey(
            '{{%payroll_adjustment__d_driver}}',
            '{{%payroll_adjustment}}'
        );
        $this->dropForeignKey(
            '{{%payroll_adjustment__d_vendor}}',
            '{{%payroll_adjustment}}'
        );
        $this->dropForeignKey(
            '{{%payroll_adjustment__d_account}}',
            '{{%payroll_adjustment}}'
        );
        $this->dropForeignKey(
            '{{%payroll_adjustment__creator}}',
            '{{%payroll_adjustment}}'
        );
        $this->dropForeignKey(
            '{{%payroll_adjustment__updater}}',
            '{{%payroll_adjustment}}'
        );
        $this->dropTable('{{%payroll_adjustment}}');
    }
}
