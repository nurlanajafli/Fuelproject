<?php

namespace common\models;

use \common\models\base\AccountingDefault as BaseAccountingDefault;
use yii\helpers\ArrayHelper;
use common\helpers\DateTime;
use common\helpers\Utils;
use common\enums\DateType;
use Yii;

/**
 * This is the model class for table "accounting_default".
 */
class AccountingDefault extends BaseAccountingDefault
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules = Utils::removeAttributeRules($rules, 'default_unclassified_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'default_escrow_holding_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'default_advances_issued_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'default_work_order_income_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'default_parts_inventory_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'default_labor_cost_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'default_misc_cost_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'primary_ar_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'default_invoice_write_off_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'default_invoice_discounts_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'default_unapplied_funds_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'default_income_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'primary_ap_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'default_bill_write_off_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'default_bill_discounts_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'default_carrier_expense_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'default_check_writing_bank_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'default_undeposited_funds_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'payroll_bank_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'payroll_expense_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'payroll_tax_expense_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'fsm_liability_account_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'futa_liability_account_id', ['exist']);
        return ArrayHelper::merge($rules, [
            [
                [
                    'default_unclassified_account_id',
                    'default_escrow_holding_account_id',
                    'default_advances_issued_account_id',
                    //'fiscal_year_start_month',
                    'default_work_order_income_account_id',
                    'default_parts_inventory_account_id',
                    'default_labor_cost_account_id',
                    'default_misc_cost_account_id',
                    'primary_ar_account_id',
                    'default_invoice_write_off_account_id',
                    'default_invoice_discounts_account_id',
                    'default_unapplied_funds_account_id',
                    'default_income_account_id',
                    'factoring_account_id',
                    //'default_invoice_process',
                    //'default_print_paper_type',
                    //'default_invoice_print_style',
                    //'default_invoice_aging_date',
                    //'default_invoice_posting_date',
                    //'default_date_for_group_invoices',
                    'primary_ap_account_id',
                    'default_bill_write_off_account_id',
                    'default_bill_discounts_account_id',
                    'default_carrier_expense_account_id',
                    'default_check_writing_bank_account_id',
                    'default_undeposited_funds_account_id',
                    //'check_printing',
                    'payroll_bank_account_id',
                    'payroll_expense_account_id',
                    'payroll_tax_expense_account_id',
                    'fsm_liability_account_id',
                    'futa_liability_account_id',
                    //'calc_method',
                    //'pay_method',
                    //'sales_commissions_calc_base'
                ], 'default', 'value' => null],
            [
                ['default_unclassified_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['default_unclassified_account_id' => 'account'],
                'filter' => Account::getFilterByType(['Expense', 'Cost Of Sales'])
            ],
            [
                ['default_escrow_holding_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['default_escrow_holding_account_id' => 'account'],
                'filter' => Account::getFilterByType('Current Liability')
            ],
            [
                ['default_advances_issued_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['default_advances_issued_account_id' => 'account'],
                'filter' => Account::getFilterByType('Current Asset')
            ],
            [['fiscal_year_start_month'], 'in', 'range' => \common\enums\Month::getEnums()],
            [
                ['default_work_order_income_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['default_work_order_income_account_id' => 'account'],
                'filter' => Account::getFilterByType(['Income', 'Other Income'])
            ],
            [
                ['default_parts_inventory_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['default_parts_inventory_account_id' => 'account'],
                'filter' => Account::getFilterByType(['Current Asset', 'Fixed Asset'])
            ],
            [
                ['default_labor_cost_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['default_labor_cost_account_id' => 'account'],
                'filter' => Account::getFilterByType(['Expense', 'Cost Of Sales'])
            ],
            [
                ['default_misc_cost_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['default_misc_cost_account_id' => 'account'],
                'filter' => Account::getFilterByType(['Expense', 'Cost Of Sales'])
            ],
            [
                ['primary_ar_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['primary_ar_account_id' => 'account'],
                'filter' => Account::getFilterByType('Accounts Receivable')
            ],
            [
                ['default_invoice_write_off_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['default_invoice_write_off_account_id' => 'account'],
                'filter' => Account::getFilterByType(['Expense', 'Cost Of Sales', 'Income', 'Other Income'])
            ],
            [
                ['default_invoice_discounts_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['default_invoice_discounts_account_id' => 'account'],
                'filter' => Account::getFilterByType(['Expense', 'Cost Of Sales', 'Income', 'Other Income'])
            ],
            [
                ['default_unapplied_funds_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['default_unapplied_funds_account_id' => 'account'],
                'filter' => Account::getFilterByType(['Current Asset', 'Fixed Asset', 'Current Liability'])
            ],
            [
                ['default_income_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['default_income_account_id' => 'account'],
                'filter' => Account::getFilterByType(['Income', 'Other Income'])
            ],
            [['default_invoice_process'], 'in', 'range' => \common\enums\InvoiceProcess::getEnums()],
            [['default_print_paper_type'], 'in', 'range' => \common\enums\PrintPaperType::getEnums()],
            [['default_invoice_print_style'], 'in', 'range' => \common\enums\InvoiceStyle::getEnums()],
            [['default_invoice_aging_date'], 'in', 'range' => DateType::getEnums()],
            [['default_invoice_posting_date'], 'in', 'range' => DateType::getEnums()],
            [['default_date_for_group_invoices'], 'in', 'range' => [DateType::SYSTEM_DATE, DateType::USER_DEFINED_DATE]],
            [
                ['primary_ap_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['primary_ap_account_id' => 'account'],
                'filter' => Account::getFilterByType('Accounts Payable')
            ],
            [
                ['default_bill_write_off_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['default_bill_write_off_account_id' => 'account'],
                'filter' => Account::getFilterByType(['Expense', 'Cost Of Sales', 'Income', 'Other Income'])
            ],
            [
                ['default_bill_discounts_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['default_bill_discounts_account_id' => 'account'],
                'filter' => Account::getFilterByType(['Expense', 'Cost Of Sales', 'Income', 'Other Income'])
            ],
            [
                ['default_carrier_expense_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['default_carrier_expense_account_id' => 'account'],
                'filter' => Account::getFilterByType(['Expense', 'Cost Of Sales'])
            ],
            [
                ['default_check_writing_bank_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['default_check_writing_bank_account_id' => 'account'],
                'filter' => Account::getFilterByType('Bank')
            ],
            [
                ['default_undeposited_funds_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['default_undeposited_funds_account_id' => 'account'],
                'filter' => Account::getFilterByType(['Bank', 'Current Asset'])
            ],
            [['check_printing'], 'in', 'range' => \common\enums\CheckPrinting::getEnums()],
            [
                ['payroll_bank_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['payroll_bank_account_id' => 'account'],
                'filter' => Account::getFilterByType('Bank')
            ],
            [
                ['payroll_expense_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['payroll_expense_account_id' => 'account'],
                'filter' => Account::getFilterByType(['Expense', 'Cost Of Sales'])
            ],
            [
                ['payroll_tax_expense_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['payroll_tax_expense_account_id' => 'account'],
                'filter' => Account::getFilterByType(['Expense', 'Cost Of Sales'])
            ],
            [
                ['fsm_liability_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['fsm_liability_account_id' => 'account'],
                'filter' => Account::getFilterByType('Current Liability')
            ],
            [
                ['futa_liability_account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::className(),
                'targetAttribute' => ['futa_liability_account_id' => 'account'],
                'filter' => Account::getFilterByType('Current Liability')
            ],
            [['calc_method'], 'in', 'range' => \common\enums\CalcMethod::getEnums()],
            [['pay_method'], 'in', 'range' => \common\enums\PayMethod::getEnums()],
            [['sales_commissions_calc_base'], 'in', 'range' => \common\enums\CalcBase::getEnums()]
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'default_unclassified_account_id' => Yii::t('app', 'Default Unclassified Account No'),
            'default_escrow_holding_account_id' => Yii::t('app', 'Default Escrow Holding Account No'),
            'default_advances_issued_account_id' => Yii::t('app', 'Default Advances Issued Account No'),
            'fiscal_year_start_month' => Yii::t('app', 'Fiscal Year Start Month'),
            'require_office_on_all_transactions' => Yii::t('app', 'Require Office On All Transactions'),
            'default_work_order_income_account_id' => Yii::t('app', 'Default Work Order Income Account No'),
            'default_parts_inventory_account_id' => Yii::t('app', 'Default Parts Inventory Account No'),
            'default_labor_cost_account_id' => Yii::t('app', 'Default Labor Cost Account No'),
            'default_misc_cost_account_id' => Yii::t('app', 'Default Misc Cost Account No'),
            'primary_ar_account_id' => Yii::t('app', 'Primary A/R  Account No'),
            'default_invoice_payment_terms' => Yii::t('app', 'Default Invoice Payment Terms'),
            'default_invoice_write_off_account_id' => Yii::t('app', 'Default Invoice Write-Off Account No'),
            'default_invoice_discounts_account_id' => Yii::t('app', 'Default Invoice Discounts Account No'),
            'default_unapplied_funds_account_id' => Yii::t('app', 'Default Unapplied Funds Account No'),
            'default_income_account_id' => Yii::t('app', 'Default Income Account No'),
            'default_invoice_item_code' => Yii::t('app', 'Default Invoice Item Code'),
            'name_on_factored_invoices_car_id' => Yii::t('app', 'Remit-To Name To Print On Factored Invoices'),
            'name_on_factored_invoices_cus_id' => Yii::t('app', 'Remit-To Name To Print On Factored Invoices'),
            'name_on_factored_invoices_ven_id' => Yii::t('app', 'Remit-To Name To Print On Factored Invoices'),
            'factoring_account_id' => Yii::t('app', 'Factoring Account ID'),
            'default_invoice_process' => Yii::t('app', 'Default Invoice Process'),
            'default_print_paper_type' => Yii::t('app', 'Default Print Paper Type'),
            'default_invoice_print_style' => Yii::t('app', 'Default Invoice Print Style'),
            'default_invoice_aging_date' => Yii::t('app', 'Default Invoice Aging Date'),
            'default_invoice_posting_date' => Yii::t('app', 'Default Invoice Posting Date'),
            'default_date_for_group_invoices' => Yii::t('app', 'Default Date For Group Invoices'),
            'apr_on_overdue_invoices' => Yii::t('app', 'APR On Overdue Invoices'),
            'include_load_notes_on_invoices' => Yii::t('app', 'Include Load Notes On Invoices'),
            'hide_header_information_on_invoices' => Yii::t('app', 'Hide Header Information On Invoices'),
            'show_trucks_and_trailers_on_invoices' => Yii::t('app', 'Show Trucks And Trailers On Invoices'),
            'show_load_details_on_third_party_invoices' => Yii::t('app', 'Show Load Details On Third-Party Invoices'),
            'post_invoice_notes_when_emailing_invoices' => Yii::t('app', 'Post Invoice Notes When Emailing Invoices'),
            'primary_ap_account_id' => Yii::t('app', 'Primary A/P Account No'),
            'default_bill_payment_terms' => Yii::t('app', 'Default Bill Payment Terms'),
            'default_bill_write_off_account_id' => Yii::t('app', 'Default Bill Write-Off Account No'),
            'default_bill_discounts_account_id' => Yii::t('app', 'Default Bill Discounts Account No'),
            'default_carrier_expense_account_id' => Yii::t('app', 'Default Carrier Expense Account No'),
            'do_not_pay_carriers_for_uninvoiced_loads' => Yii::t('app', 'Do Not Pay Carriers For Uninvoiced Loads'),
            'default_ven_iss_adv' => Yii::t('app', 'Default Vendor To Use When Issuing Advances'),
            'deduct_open_adv_pay_carriers' => Yii::t('app', 'Deduct Open Advances When Paying Carriers'),
            'default_check_writing_bank_account_id' => Yii::t('app', 'Default Check Writing Bank Account No'),
            'default_undeposited_funds_account_id' => Yii::t('app', 'Default Undeposited Funds Account No'),
            'check_printing' => Yii::t('app', 'Check Printing'),
            'payroll_bank_account_id' => Yii::t('app', 'Payroll Bank Account'),
            'payroll_expense_account_id' => Yii::t('app', 'Payroll Expense Account'),
            'payroll_tax_expense_account_id' => Yii::t('app', 'Payroll Tax Expense Account'),
            'fsm_liability_account_id' => Yii::t('app', 'FIT/SS/Med Liability Account'),
            'futa_liability_account_id' => Yii::t('app', 'FUTA Liability Account'),
            'calc_method' => Yii::t('app', 'Calc Method'),
            'pay_method' => Yii::t('app', 'Pay Method'),
            'per_diem_rate' => Yii::t('app', 'Per Diem Rate'),
            'to_show_adv_balances_on_settlements' => Yii::t('app', 'Show Advances Balances on Settlements'),
            'to_show_escrow_balances_on_settlements' => Yii::t('app', 'Show Escrow Balances on Settlements'),
            'd_show_adv_balances_on_settlements' => Yii::t('app', 'Show Advances Balances on Settlements'),
            'd_show_escrow_balances_on_settlements' => Yii::t('app', 'Show Escrow Balances on Settlements'),
            'sales_commissions_calc_base' => Yii::t('app', 'Calculate Sales Commissions Based On'),
            'sales_commission_override_percentage' => Yii::t('app', 'Sales Commission Override Percentage'),
            'deduct_load_expenses_from_commissions' => Yii::t('app', 'Deduct Load Expenses From Commissions')
        ]);
    }
}
