<?php

use common\enums\FuelCardDataProvider;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%fuel_product_codes}}`.
 */
class m211104_055500_create_fuel_product_codes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%fuel_product_codes}}', [
            'id' => $this->primaryKey(),
            'provider' => $this->string(32)->notNull(),
            'description' => $this->string(64),
            'oo_acct' => $this->string(4)->comment('Owner Operator Account'),
            'cd_acct' => $this->string(4)->comment('Company Driver Account'),
            'fee_amt' => $this->decimal(10, 2)->defaultValue(0),
            'fee_acct' => $this->string(4)
        ]);
        $this->createIndex(
            'idx_unique_fuel_product_codes_prov_desc',
            '{{%fuel_product_codes}}',
            ['provider', 'description'],
            true
        );
        $this->addForeignKey(
            'fk_fuel_product_codes_oo_acct',
            '{{%fuel_product_codes}}',
            'oo_acct',
            '{{%account}}',
            'account',
            'RESTRICT',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk_fuel_product_codes_cd_acct',
            '{{%fuel_product_codes}}',
            'cd_acct',
            '{{%account}}',
            'account',
            'RESTRICT',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk_fuel_product_codes_fee_acct',
            '{{%fuel_product_codes}}',
            'fee_acct',
            '{{%account}}',
            'account',
            'RESTRICT',
            'RESTRICT'
        );

        $efs = FuelCardDataProvider::EFS;
        $this->batchInsert('{{%fuel_product_codes}}', ['provider', 'description'], [
            [$efs, 'ADDITIVES'],
            [$efs, 'CASH ADVANCE'],
            [$efs, 'DEF PURCHASED AT PUMP'],
            [$efs, 'DIESEL EXHAUST FLUID'],
            [$efs, 'DISCOUNT'],
            [$efs, 'FEDERAL TAX'],
            [$efs, 'FULL DIESEL # 1'],
            [$efs, 'FULL DIESEL # 2'],
            [$efs, 'MINI DIESEL # 1'],
            [$efs, 'MINI DIESEL # 2'],
            [$efs, 'NON HWY GAS'],
            [$efs, 'OIL'],
            [$efs, 'PACKAGED DEF'],
            [$efs, 'PARTS'],
            [$efs, 'REEFER FUEL'],
            [$efs, 'REPAIR/LABOR'],
            [$efs, 'SALES TAX'],
            [$efs, 'SCALES'],
            [$efs, 'SELF DIESEL # 1'],
            [$efs, 'SELF DIESEL # 2'],
            [$efs, 'STATE TAX'],
            [$efs, 'TIRE/TIRE REPAIR'],
            [$efs, 'TRANSACTION FEE'],
            [$efs, 'TRANSCHECK'],
            [$efs, 'TRANSCHECK FEE'],
            [$efs, 'TUBE'],
            [$efs, 'WINTER DIESEL # 1'],
            [$efs, 'WINTER DIESEL # 2'],
        ]);

        $tch = FuelCardDataProvider::TCH;
        $this->batchInsert('{{%fuel_product_codes}}', ['provider', 'description'], [
            [$tch, 'Adtv'],
            [$tch, 'CashAdv'],
            [$tch, 'CashFee'],
            [$tch, 'DiesEx'],
            [$tch, 'DrvExp'],
            [$tch, 'EmrRep'],
            [$tch, 'Lub'],
            [$tch, 'Misc'],
            [$tch, 'Oil'],
            [$tch, 'Parts'],
            [$tch, 'Reb'],
            [$tch, 'RefFul'],
            [$tch, 'Scales'],
            [$tch, 'TirPur'],
            [$tch, 'TirRep'],
            [$tch, 'TranFee'],
            [$tch, 'TrkFul'],
            [$tch, 'TrkRep'],
            [$tch, 'TrlExp'],
        ]);

        $knox = FuelCardDataProvider::KNOX;
        $this->batchInsert('{{%fuel_product_codes}}', ['provider', 'description'], [
            [$knox, 'TrkFul'],
        ]);

        $tchek = FuelCardDataProvider::T_CHEK;
        $this->batchInsert('{{%fuel_product_codes}}', ['provider', 'description'], [
            [$tchek, 'Additives'],
            [$tchek, 'Adjustment Trans.'],
            [$tchek, 'Cash'],
            [$tchek, 'Charge of Oil'],
            [$tchek, 'Clean Trailer'],
            [$tchek, 'Diesel Exhaust Fluid'],
            [$tchek, 'Discount'],
            [$tchek, 'Express Check Trans.'],
            [$tchek, 'Fee'],
            [$tchek, 'FullServe Diesel 1'],
            [$tchek, 'FullServe Diesel 2'],
            [$tchek, 'Gas'],
            [$tchek, 'Ice'],
            [$tchek, 'Inspection'],
            [$tchek, 'Local & State Tax'],
            [$tchek, 'LP Gas'],
            [$tchek, 'Lube'],
            [$tchek, 'Meals'],
            [$tchek, 'MiniServe Diesel 1'],
            [$tchek, 'MiniServe Diesel 2'],
            [$tchek, 'Minor Parts'],
            [$tchek, 'Minor Repairs'],
            [$tchek, 'Oil'],
            [$tchek, 'Other'],
            [$tchek, 'Paycheck Trans.'],
            [$tchek, 'Permits'],
            [$tchek, 'Personal Funds'],
            [$tchek, 'Preventative Maint.'],
            [$tchek, 'Reefer Fuel'],
            [$tchek, 'Room'],
            [$tchek, 'Scales'],
            [$tchek, 'SelfServe Diesel 1'],
            [$tchek, 'SelfServe Diesel 2'],
            [$tchek, 'Tire Purchase'],
            [$tchek, 'Tire Repair'],
            [$tchek, 'Tube'],
            [$tchek, 'Wash'],
        ]);

        $comdata = FuelCardDataProvider::COMDATA;
        $this->batchInsert('{{%fuel_product_codes}}', ['provider', 'description', 'oo_acct', 'cd_acct'], [
            [$comdata, 'Adtv',          '1050', '4099'],
            [$comdata, 'AntiFreeze',    '1050', '4099'],
            [$comdata, 'Aviation',      '1050', '4099'],
            [$comdata, 'AvMaint',       '1050', '4099'],
            [$comdata, 'CashAdv',       '1050', '1050'],
            [$comdata, 'CashFee',       '1050', '1050'],
            [$comdata, 'DiesEx',        '1050', '4020'],
            [$comdata, 'DrvExp',        '1050', '4099'],
            [$comdata, 'EmrRep',        '1050', '4030'],
            [$comdata, 'Groceries',     '1050', '4099'],
            [$comdata, 'Hotel',         '1050', '4099'],
            [$comdata, 'Labor',         '1050', '4099'],
            [$comdata, 'Lub',           '1050', '4030'],
            [$comdata, 'Misc',          '1050', '4099'],
            [$comdata, 'Oil',           '1050', '4030'],
            [$comdata, 'Parts',         '1050', '4030'],
            [$comdata, 'PrkSvc',        '1050', '4030'],
            [$comdata, 'Reb',           '4020', '4020'],
            [$comdata, 'RefFul',        '1050', '4020'],
            [$comdata, 'Regulatory',    '1050', '4099'],
            [$comdata, 'Scales',        '1050', '4099'],
            [$comdata, 'Shower',        '1050', '4099'],
            [$comdata, 'TirPur',        '1050', '4030'],
            [$comdata, 'TirRep',        '1050', '4030'],
            [$comdata, 'Tolls',         '1050', '4099'],
            [$comdata, 'TranFee',       '1050', '4099'],
            [$comdata, 'TripScan',      '1050', '4099'],
            [$comdata, 'TrkFul',        '1050', '4020'],
            [$comdata, 'TrkRep',        '1050', '4020'],
            [$comdata, 'TrkWash',       '1050', '4099'],
            [$comdata, 'TrlExp',        '1050', '4040'],
            [$comdata, 'VehMaint',      '1050', '4030'],
            [$comdata, 'WiperFl',       '1050', '4030'],
        ]);

        $comdataMc = FuelCardDataProvider::COMDATA_MC;
        $this->batchInsert('{{%fuel_product_codes}}', ['provider', 'description'], [
            [$comdataMc, 'Adtv'],
            [$comdataMc, 'Aviation'],
            [$comdataMc, 'AvMaint'],
            [$comdataMc, 'CashAdv'],
            [$comdataMc, 'CashFee'],
            [$comdataMc, 'DiesEx'],
            [$comdataMc, 'DrvExp'],
            [$comdataMc, 'EmrRep'],
            [$comdataMc, 'Groceries'],
            [$comdataMc, 'Hotel'],
            [$comdataMc, 'Labor'],
            [$comdataMc, 'Lub'],
            [$comdataMc, 'Misc'],
            [$comdataMc, 'Oil'],
            [$comdataMc, 'Parts'],
            [$comdataMc, 'PrkSvc'],
            [$comdataMc, 'Reb'],
            [$comdataMc, 'RefFul'],
            [$comdataMc, 'Regulatory'],
            [$comdataMc, 'Scales'],
            [$comdataMc, 'Shower'],
            [$comdataMc, 'TirPur'],
            [$comdataMc, 'TirRep'],
            [$comdataMc, 'Tolls'],
            [$comdataMc, 'TranFee'],
            [$comdataMc, 'TripScan'],
            [$comdataMc, 'TrkFul'],
            [$comdataMc, 'TrkRep'],
            [$comdataMc, 'TrkWash'],
            [$comdataMc, 'TrlExp'],
            [$comdataMc, 'VehMaint'],
        ]);

        $multiService = FuelCardDataProvider::MULTI_SERVICE;
        $this->batchInsert('{{%fuel_product_codes}}', ['provider', 'description'], [
            [$multiService, 'CashAdv'],
            [$multiService, 'DiesEx'],
            [$multiService, 'Oil'],
            [$multiService, 'Reb'],
            [$multiService, 'RefFul'],
            [$multiService, 'TranFee'],
            [$multiService, 'TrkFul'],
        ]);

        $fleetOne = FuelCardDataProvider::FLEET_ONE;
        $this->batchInsert('{{%fuel_product_codes}}', ['provider', 'description'], [
            [$fleetOne, 'Cash Advance'],
            [$fleetOne, 'Diesel Fuel'],
            [$fleetOne, 'Discounts'],
            [$fleetOne, 'Dispatch Fee'],
            [$fleetOne, 'Fuel Additives'],
            [$fleetOne, 'Gasoline'],
            [$fleetOne, 'Inv Trans Fee'],
            [$fleetOne, 'Meals'],
            [$fleetOne, 'Misc.'],
            [$fleetOne, 'Motel'],
            [$fleetOne, 'Oil'],
            [$fleetOne, 'Parts'],
            [$fleetOne, 'Permit'],
            [$fleetOne, 'Plus Check Draft'],
            [$fleetOne, 'Plus Check Fee'],
            [$fleetOne, 'Reefer Fuel'],
            [$fleetOne, 'Sales Tax'],
            [$fleetOne, 'Scales Fee'],
            [$fleetOne, 'Tire Purchase'],
            [$fleetOne, 'Tire Repair'],
            [$fleetOne, 'Transaction Charge'],
            [$fleetOne, 'Wash'],
        ]);

        $tchCheck = FuelCardDataProvider::TCH_CHECK;
        $this->batchInsert('{{%fuel_product_codes}}', ['provider', 'description'], [
            [$tchCheck, 'CashFee'],
            [$tchCheck, 'TranFee'],
        ]);

        $comCheck = FuelCardDataProvider::COM_CHEK;
        $this->batchInsert('{{%fuel_product_codes}}', ['provider', 'description'], [
            [$comCheck, 'Express Check Trans'],
            [$comCheck, 'Fee'],
        ]);

        $tolls = FuelCardDataProvider::TOLLS;
        $this->batchInsert('{{%fuel_product_codes}}', ['provider', 'description', 'oo_acct', 'cd_acct'], [
            [$tolls, '', '1050', '4099'],
        ]);

        $tolls2 = FuelCardDataProvider::TOLLS2;
        $this->batchInsert('{{%fuel_product_codes}}', ['provider', 'description', 'oo_acct', 'cd_acct'], [
            [$tolls2, '', '1050', '4099'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%fuel_product_codes}}');
    }
}
