<?php

use common\enums\AccountFilter;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%account}}`.
 */
class m210114_133132_create_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%account}}', [
            'account' => $this->string(4)->notNull(),
            'description' => $this->string()->notNull(),
            'account_type' => $this->integer()->notNull(),
            'filter' => $this->string()->notNull(),
            'active' => $this->boolean()->notNull()->defaultValue(true),
            'hidden' => $this->boolean()->notNull()->defaultValue(false),
        ]);
        $this->addPrimaryKey('account_pk', '{{%account}}', 'account');
        $this->addForeignKey(
            '{{%account_account_type_fk}}',
            '{{%account}}',
            'account_type',
            '{{%account_type}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $bank = \common\models\AccountType::findOne(['type' => 'Bank'])->id;
        $accountReceivable = \common\models\AccountType::findOne(['type' => 'Accounts Receivable'])->id;
        $currentAsset = \common\models\AccountType::findOne(['type' => 'Current Asset'])->id;
        $fixedAsset = \common\models\AccountType::findOne(['type' => 'Fixed Asset'])->id;
        $accountsPayable = \common\models\AccountType::findOne(['type' => 'Accounts Payable'])->id;
        $currentLiability = \common\models\AccountType::findOne(['type' => 'Current Liability'])->id;
        $income = \common\models\AccountType::findOne(['type' => 'Income'])->id;
        $costOfSales = \common\models\AccountType::findOne(['type' => 'Cost Of Sales'])->id;
        $expense = \common\models\AccountType::findOne(['type' => 'Expense'])->id;
        $otherIncome = \common\models\AccountType::findOne(['type' => 'Other Income'])->id;
        $equity = \common\models\AccountType::findOne(['type' => 'Equity'])->id;

        $this->batchInsert("{{%account}}", ['account', 'description', 'account_type', 'filter'], [
            ['1000', 'Bank of America Account',                 $bank,              AccountFilter::GENERAL],
            ['1030', 'Receivables',                             $accountReceivable, AccountFilter::GENERAL],
            ['1031', 'TAFS - Factored Invoices',                $currentAsset,      AccountFilter::GENERAL],
            ['1033', 'TAFS - Reserve',                          $currentAsset,      AccountFilter::GENERAL],
            ['1050', 'Advances Given',                          $currentAsset,      AccountFilter::ADVANCE],
            ['1055', 'Loans Given',                             $currentAsset,      AccountFilter::ADVANCE],
            ['1056', 'Deductible',                              $currentAsset,      AccountFilter::ADVANCE],
            ['1100', 'Undeposited Funds',                       $currentAsset,      AccountFilter::GENERAL],
            ['1500', 'Parts Inventory',                         $currentAsset,      AccountFilter::GENERAL],
            ['1800', 'Land',                                    $fixedAsset,        AccountFilter::GENERAL],
            ['1801', 'Building',                                $fixedAsset,        AccountFilter::GENERAL],

            ['2010', 'Payables',                                $accountsPayable,   AccountFilter::GENERAL],
            ['2020', 'Driver Escrow',                           $currentLiability,  AccountFilter::ESCROW],
            ['2030', 'Payroll Taxes Due',                       $currentLiability,  AccountFilter::GENERAL],
            ['2031', 'Child Support Withheld',                  $currentLiability,  AccountFilter::GENERAL],
            ['2100', 'Unapplied Funds',                         $currentLiability,  AccountFilter::GENERAL],
            ['2200', 'TAFS - Notes Receivable',                 $currentLiability,  AccountFilter::GENERAL],

            ['3000', 'Transportation Revenue',                  $income,            AccountFilter::GENERAL],
            ['3001', 'Driver charges',                          $income,            AccountFilter::GENERAL],
            ['3010', 'Accessorial Revenue',                     $income,            AccountFilter::GENERAL],
            ['3020', 'Truck Lease',                             $income,            AccountFilter::GENERAL],
            ['3025', '13 Cents Per Mile',                       $income,            AccountFilter::GENERAL],
            ['3030', 'Trailer Fee',                             $income,            AccountFilter::GENERAL],

            ['4000', 'Purchased Transportation',                $costOfSales,       AccountFilter::GENERAL],
            ['4020', 'Truck Fuel',                              $costOfSales,       AccountFilter::GENERAL],
            ['4030', 'Truck Maintenance',                       $costOfSales,       AccountFilter::GENERAL],
            ['4031', 'Truck Tires',                             $costOfSales,       AccountFilter::GENERAL],
            ['4040', 'Trailer Maintenance',                     $costOfSales,       AccountFilter::GENERAL],
            ['4041', 'Trailer Tires',                           $costOfSales,       AccountFilter::GENERAL],
            ['4050', 'Driver Payroll',                          $costOfSales,       AccountFilter::GENERAL],
            ['4051', 'Driver Payroll Taxes',                    $costOfSales,       AccountFilter::GENERAL],
            ['4052', 'Driver Benefits - Health Insurance',      $costOfSales,       AccountFilter::GENERAL],
            ['4053', 'Driver Benefits - Other',                 $costOfSales,       AccountFilter::GENERAL],
            ['4099', 'Misc Transportation',                     $costOfSales,       AccountFilter::GENERAL],
            ['4100', 'Insurance',                               $costOfSales,       AccountFilter::GENERAL],
            ['4200', 'Freight Claims',                          $costOfSales,       AccountFilter::GENERAL],
            ['4300', 'Vehicle Licensing',                       $costOfSales,       AccountFilter::GENERAL],

            ['5000', 'Employee Payroll',                        $expense,           AccountFilter::GENERAL],
            ['5001', 'Employee Payroll Taxes',                  $expense,           AccountFilter::GENERAL],
            ['5002', 'Employee Benefits - Health Insurance',    $expense,           AccountFilter::GENERAL],
            ['5003', 'Employee Benefits - Other',               $expense,           AccountFilter::GENERAL],
            ['5010', 'Invoice Discounts Given',                 $expense,           AccountFilter::GENERAL],
            ['5011', 'Invoice Adjustments',                     $expense,           AccountFilter::GENERAL],
            ['5020', 'Phones - Office',                         $expense,           AccountFilter::GENERAL],
            ['5021', 'Phones - Mobile',                         $expense,           AccountFilter::GENERAL],
            ['5022', 'Internet Access',                         $expense,           AccountFilter::GENERAL],
            ['5200', 'Office Rent',                             $expense,           AccountFilter::GENERAL],
            ['5201', 'Office Utilities',                        $expense,           AccountFilter::GENERAL],
            ['5202', 'Office Supplies',                         $expense,           AccountFilter::GENERAL],
            ['5203', 'Office Expense',                          $expense,           AccountFilter::GENERAL],
            ['5204', 'Office Equipment',                        $expense,           AccountFilter::GENERAL],
            ['5300', 'Operations Software',                     $expense,           AccountFilter::GENERAL],
            ['5301', 'Data Center Hosting',                     $expense,           AccountFilter::GENERAL],
            ['5302', 'Computer Hardware',                       $expense,           AccountFilter::GENERAL],
            ['5400', 'Permits',                                 $expense,           AccountFilter::GENERAL],
            ['5405', 'Tolls',                                   $expense,           AccountFilter::GENERAL],
            ['5406', 'Other Driver Charges',                    $otherIncome,       AccountFilter::GENERAL],
            ['5800', 'Vehicle Expenses',                        $expense,           AccountFilter::GENERAL],
            ['5801', 'Vehicle Fuel',                            $expense,           AccountFilter::GENERAL],
            ['5802', 'Vehicle Maintenance',                     $expense,           AccountFilter::GENERAL],
            ['5900', 'Professional Fees',                       $expense,           AccountFilter::GENERAL],
            ['5901', 'Advertising',                             $expense,           AccountFilter::GENERAL],
            ['5950', 'Interest Expense',                        $expense,           AccountFilter::GENERAL],
            ['5951', 'TAFS - Factoring Fees',                   $expense,           AccountFilter::GENERAL],
            ['5952', 'Wire Fees',                               $expense,           AccountFilter::GENERAL],

            ['6000', 'Travel Expenses',                         $expense,           AccountFilter::GENERAL],
            ['6001', 'Travel - Lodging',                        $expense,           AccountFilter::GENERAL],
            ['6002', 'Travel - Meals & Entertainment',          $expense,           AccountFilter::GENERAL],
            ['6003', 'Travel - Airline Tickets',                $expense,           AccountFilter::GENERAL],
            ['6004', 'Travel - Car Rentals',                    $expense,           AccountFilter::GENERAL],
            ['6005', 'Travel - Other Expenses',                 $expense,           AccountFilter::GENERAL],

            ['9000', 'Taxes',                                   $expense,           AccountFilter::GENERAL],
            ['9001', 'Federal Business Income Taxes',           $expense,           AccountFilter::GENERAL],
            ['9002', 'State Business Income Taxes',             $expense,           AccountFilter::GENERAL],
            ['9003', 'Other Business Taxes',                    $expense,           AccountFilter::GENERAL],
            ['9500', 'Other Equity',                            $equity,            AccountFilter::GENERAL],
            ['9999', 'Unclassified',                            $expense,           AccountFilter::GENERAL],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%account_account_type_fk}}',
            '{{%account}}'
        );
        $this->dropTable('{{%account}}');
    }
}
