<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%accounting_default}}`.
 */
class m210624_060705_create_accounting_default_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%accounting_default}}', [
            'id' => $this->primaryKey(),
            'default_unclassified_account_id' => $this->string(),
            'default_escrow_holding_account_id' => $this->string(),
            'default_advances_issued_account_id' => $this->string(),
            'fiscal_year_start_month' => $this->string(),
            'require_office_on_all_transactions' => $this->boolean()->notNull(),
            'default_work_order_income_account_id' => $this->string(),
            'default_parts_inventory_account_id' => $this->string(),
            'default_labor_cost_account_id' => $this->string(),
            'default_misc_cost_account_id' => $this->string(),
            'primary_ar_account_id' => $this->string(),
            'default_invoice_payment_terms' => $this->integer(),
            'default_invoice_write_off_account_id' => $this->string(),
            'default_invoice_discounts_account_id' => $this->string(),
            'default_unapplied_funds_account_id' => $this->string(),
            'default_income_account_id' => $this->string(),
            'default_invoice_item_code' => $this->integer(),
            'name_on_factored_invoices_car_id' => $this->integer(),
            'name_on_factored_invoices_cus_id' => $this->integer(),
            'name_on_factored_invoices_ven_id' => $this->integer(),
            'factoring_account_id' => $this->string(),
            'default_invoice_process' => $this->string(),
            'default_print_paper_type' => $this->string(),
            'default_invoice_print_style' => $this->string(),
            'default_invoice_aging_date' => $this->string(),
            'default_invoice_posting_date' => $this->string(),
            'default_date_for_group_invoices' => $this->string(),
            'apr_on_overdue_invoices' => $this->decimal(10, 2)->defaultValue(0),
            'include_load_notes_on_invoices' => $this->boolean()->notNull(),
            'hide_header_information_on_invoices' => $this->boolean()->notNull(),
            'show_trucks_and_trailers_on_invoices' => $this->boolean()->notNull(),
            'show_load_details_on_third_party_invoices' => $this->boolean()->notNull(),
            'post_invoice_notes_when_emailing_invoices' => $this->boolean()->notNull(),
            'primary_ap_account_id' => $this->string(),
            'default_bill_payment_terms' => $this->integer(),
            'default_bill_write_off_account_id' => $this->string(),
            'default_bill_discounts_account_id' => $this->string(),
            'default_carrier_expense_account_id' => $this->string(),
            'do_not_pay_carriers_for_uninvoiced_loads' => $this->boolean()->notNull(),
            'default_ven_iss_adv' => $this->integer(),
            'deduct_open_adv_pay_carriers' => $this->boolean()->notNull(),
            'default_check_writing_back_account_id' => $this->string(),
            'default_undeposited_funds_account_id' => $this->string(),
            'check_printing' => $this->string(),
            'payroll_bank_account_id' => $this->string(),
            'payroll_expense_account_id' => $this->string(),
            'payroll_tax_expense_account_id' => $this->string(),
            'fsm_liability_account_id' => $this->string(),
            'futa_liability_account_id' => $this->string(),
            'calc_method' => $this->string(),
            'pay_method' => $this->string(),
            'per_diem_rate' => $this->decimal(10, 2),
            'to_show_adv_balances_on_settlements' => $this->boolean()->notNull(),
            'to_show_escrow_balances_on_settlements' => $this->boolean()->notNull(),
            'd_show_adv_balances_on_settlements' => $this->boolean()->notNull(),
            'd_show_escrow_balances_on_settlements' => $this->boolean()->notNull(),
            'sales_commissions_calc_base' => $this->string(),
            'sales_commission_override_percentage' => $this->decimal(10, 2),
            'deduct_load_expenses_from_commissions' => $this->boolean()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ]);
        $this->addForeignKey(
            '{{%accounting_default_default_unclassified_ac_fk}}',
            '{{%accounting_default}}',
            'default_unclassified_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_escrow_holding_ac_fk}}',
            '{{%accounting_default}}',
            'default_escrow_holding_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_advances_issued_ac_fk}}',
            '{{%accounting_default}}',
            'default_advances_issued_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_work_order_income_ac_fk}}',
            '{{%accounting_default}}',
            'default_work_order_income_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_parts_inventory_ac_fk}}',
            '{{%accounting_default}}',
            'default_parts_inventory_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_labor_cost_ac_fk}}',
            '{{%accounting_default}}',
            'default_labor_cost_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_misc_cost_ac_fk}}',
            '{{%accounting_default}}',
            'default_misc_cost_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_primary_ar_ac_fk}}',
            '{{%accounting_default}}',
            'primary_ar_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_invoice_payment_terms_fk}}',
            '{{%accounting_default}}',
            'default_invoice_payment_terms',
            '{{%payment_term_code}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_invoice_write_off_ac_fk}}',
            '{{%accounting_default}}',
            'default_invoice_write_off_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_invoice_discounts_ac_fk}}',
            '{{%accounting_default}}',
            'default_invoice_discounts_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_unapplied_funds_ac_fk}}',
            '{{%accounting_default}}',
            'default_unapplied_funds_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_income_ac_fk}}',
            '{{%accounting_default}}',
            'default_income_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_invoice_item_code_fk}}',
            '{{%accounting_default}}',
            'default_invoice_item_code',
            '{{%invoice_item_code}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_name_on_factored_invoices_car_id_fk}}',
            '{{%accounting_default}}',
            'name_on_factored_invoices_car_id',
            '{{%carrier}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_name_on_factored_invoices_cus_id_fk}}',
            '{{%accounting_default}}',
            'name_on_factored_invoices_cus_id',
            '{{%customer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_name_on_factored_invoices_ven_id_fk}}',
            '{{%accounting_default}}',
            'name_on_factored_invoices_ven_id',
            '{{%vendor}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_primary_ap_ac_fk}}',
            '{{%accounting_default}}',
            'primary_ap_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_bill_payment_terms_fk}}',
            '{{%accounting_default}}',
            'default_bill_payment_terms',
            '{{%payment_term_code}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_bill_write_off_ac_fk}}',
            '{{%accounting_default}}',
            'default_bill_write_off_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_bill_discounts_ac_fk}}',
            '{{%accounting_default}}',
            'default_bill_discounts_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_carrier_expense_ac_fk}}',
            '{{%accounting_default}}',
            'default_carrier_expense_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_ven_iss_adv_fk}}',
            '{{%accounting_default}}',
            'default_ven_iss_adv',
            '{{%vendor}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_check_writing_back_ac_fk}}',
            '{{%accounting_default}}',
            'default_check_writing_back_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_default_undeposited_funds_ac_fk}}',
            '{{%accounting_default}}',
            'default_undeposited_funds_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_payroll_bank_ac_fk}}',
            '{{%accounting_default}}',
            'payroll_bank_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_payroll_expense_ac_fk}}',
            '{{%accounting_default}}',
            'payroll_expense_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_payroll_tax_expense_ac_fk}}',
            '{{%accounting_default}}',
            'payroll_tax_expense_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_fsm_liability_ac_fk}}',
            '{{%accounting_default}}',
            'fsm_liability_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_futa_liability_ac_fk}}',
            '{{%accounting_default}}',
            'futa_liability_account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_created_by_fk}}',
            '{{%accounting_default}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%accounting_default_updated_by_fk}}',
            '{{%accounting_default}}',
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
            '{{%accounting_default_updated_by_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_created_by_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_futa_liability_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_fsm_liability_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_payroll_tax_expense_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_payroll_expense_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_payroll_bank_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_undeposited_funds_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_check_writing_back_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_ven_iss_adv_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_carrier_expense_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_bill_discounts_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_bill_write_off_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_bill_payment_terms_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_primary_ap_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_name_on_factored_invoices_ven_id_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_name_on_factored_invoices_cus_id_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_name_on_factored_invoices_car_id_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_invoice_item_code_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_income_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_unapplied_funds_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_invoice_discounts_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_invoice_write_off_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_invoice_payment_terms_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_primary_ar_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_misc_cost_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_labor_cost_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_parts_inventory_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_work_order_income_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_advances_issued_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_escrow_holding_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropForeignKey(
            '{{%accounting_default_default_unclassified_ac_fk}}',
            '{{%accounting_default}}'
        );
        $this->dropTable('{{%accounting_default}}');
    }
}
