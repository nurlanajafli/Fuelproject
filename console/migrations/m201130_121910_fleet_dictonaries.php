<?php

use yii\db\Migration;
use \common\enums\VehicleServiceEquip;
use \common\enums\PartsCategoryEquip;

/**
 * Class m201130_121910_fleet_dictonaries
 */
class m201130_121910_fleet_dictonaries extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%log_violation_code}}', [
            'code' => $this->string()->notNull(),
            'description' => $this->string(),
        ]);
        $this->addPrimaryKey('{{%log_violation_code_code_pk}}', '{{%log_violation_code}}', 'code');
        $this->batchInsert('{{%log_violation_code}}', ['code', 'description'], [
            ['CI', 'Crash Indicator'],
            ['CS', 'Controlled Substance'],
            ['DF', 'Driver Fitness'],
            ['DM', 'Date Missing'],
            ['HM', 'Hazardous Materials'],
            ['HS', 'HOS Compliance'],
            ['IG', 'Incomplete Grid'],
            ['MM', 'Miles Missing'],
            ['NE', 'Log Total <> Grid'],
            ['NS', 'No Signature'],
            ['OH', 'Over Hours'],
            ['OT', 'Other'],
            ['PO', 'No Post Trip'],
            ['PR', 'No Pre Trip'],
            ['S1', '6-10 mph'],
            ['S2', '10-14 mph'],
            ['S3', '15-over mph'],
            ['UD', 'Unsafe Driving'],
        ]);

        $this->createTable('{{%vehicle_service_code}}', [
            'code' => $this->string()->notNull(),
            'equip' => $this->string()->notNull(),
            'days' => $this->integer()->defaultValue(0),
            'miles' => $this->integer()->defaultValue(0),
            'description' => $this->string(),
        ]);
        $this->addPrimaryKey('{{%vehicle_service_code_code_pk}}', '{{%vehicle_service_code}}', 'code');
        $this->batchInsert('{{%vehicle_service_code}}', ['code', 'equip', 'days', 'miles', 'description'], [
            ['AC', VehicleServiceEquip::TRUCK, 0, 0, 'A/C'],
            ['ACCREP TRK', VehicleServiceEquip::TRUCK, 0, 0, 'Accident Repair on a Truck'],
            ['ACCREP TRL', VehicleServiceEquip::TRAILER, 0, 0, 'Accident Repair on a Trailer'],
            ['ALIGN', VehicleServiceEquip::TRUCK, 0, 0, 'Alignment'],
            ['BOB INS', VehicleServiceEquip::TRUCK, 0, 0, 'Bobtail Insurance'],
            ['BRKSVC TRK', VehicleServiceEquip::TRUCK, 0, 0, 'Brake Service Truck'],
            ['BRKSVC TRL', VehicleServiceEquip::TRAILER, 0, 0, 'Brake Service Trailer'],
            ['ELECT TRK', VehicleServiceEquip::TRUCK, 0, 0, 'Electrical Truck'],
            ['ELECT TRL', VehicleServiceEquip::TRAILER, 0, 0, 'Electrical Trailer'],
            ['ENGREPAIR', VehicleServiceEquip::TRUCK, 0, 0, 'Engine Repair'],
            ['FRAME', VehicleServiceEquip::TRAILER, 0, 0, 'Frame / Subframe Repair'],
            ['GENREP TRL', VehicleServiceEquip::TRUCK, 0, 0, 'General Repair on a Truck'],
            ['GLASS TRK', VehicleServiceEquip::TRUCK, 0, 0, 'Windshield Repair Truck'],
            ['LNDGEAR', VehicleServiceEquip::TRAILER, 0, 0, 'Landing Gear Repair'],
            ['OILCHANGE', VehicleServiceEquip::TRUCK, 90, 40000, 'Oil Change'],
            ['PERMITNY', VehicleServiceEquip::TRUCK, 365, 0, 'NY Permit'],
            ['PL EXP TRK', VehicleServiceEquip::TRUCK, 365, 0, 'Truck Plate Expiration'],
            ['PL EXP TRL', VehicleServiceEquip::TRAILER, 0, 0, 'Trailer Plate Expiration'],
            ['PM SVC A', VehicleServiceEquip::TRUCK, 90, 40000, 'TRUCK PM SERVICE'],
            ['REFSVC', VehicleServiceEquip::TRAILER, 0, 0, 'Reefer Service'],
            ['ROOF TRL', VehicleServiceEquip::TRAILER, 0, 0, 'Roof Trailer'],
            ['TIRES', VehicleServiceEquip::TRUCK, 0, 0, 'Tires'],
            ['TRANSSVC', VehicleServiceEquip::TRUCK, 0, 0, 'Transmission/Drive Train'],
            ['TRKINSPA', VehicleServiceEquip::TRUCK, 0, 0, 'Annual DOT Inspection'],
            ['TRKINSPP', VehicleServiceEquip::TRUCK, 0, 0, 'Periodic Inspection'],
            ['TRLBRAKES', VehicleServiceEquip::TRAILER, 0, 0, 'Brake Service'],
            ['TRLGENREP', VehicleServiceEquip::TRAILER, 0, 0, 'Trailer General Repair'],
            ['TRLINSPA', VehicleServiceEquip::TRAILER, 0, 0, 'Annual DOT Inspection'],
            ['TRLINSPP', VehicleServiceEquip::TRAILER, 0, 0, 'Periodic Inspection'],
            ['TRLTIRES', VehicleServiceEquip::TRAILER, 0, 0, 'Tires'],
            ['TRUCK WASH', VehicleServiceEquip::TRUCK, 0, 0, 'TRUCK WASH'],
        ]);

        $this->createTable('{{%parts_category}}', [
            'code' => $this->string()->notNull(),
            'equip' => $this->string()->notNull(),
            'days' => $this->integer()->defaultValue(0),
            'miles' => $this->integer()->defaultValue(0),
            'description' => $this->string(),
        ]);
        $this->addPrimaryKey('{{%parts_category_code_pk}}', '{{%parts_category}}', 'code');
        $this->batchInsert('{{%parts_category}}', ['code', 'equip', 'description'], [
            ['ELEC', PartsCategoryEquip::TRUCK, 'Electrical Components'],
            ['ENG', PartsCategoryEquip::TRUCK, 'Engine Parts'],
            ['LUBE', PartsCategoryEquip::TRUCK, 'Lubricants'],
            ['MISC', PartsCategoryEquip::TRUCK, 'Miscellaneous Parts'],
            ['OIL', PartsCategoryEquip::TRUCK, 'Oils'],
            ['SHOP', PartsCategoryEquip::TRUCK, 'Shop Supplies'],
            ['SUSP', PartsCategoryEquip::TRUCK, 'Suspension Components'],
            ['TKBR', PartsCategoryEquip::TRUCK, 'Truck Brakes'],
            ['TKTR', PartsCategoryEquip::TRUCK, 'Truck Tires'],
            ['TLBR', PartsCategoryEquip::TRAILER, 'Trailer Brakes'],
            ['TLLGT', PartsCategoryEquip::TRAILER, 'Trailer Lighting'],
            ['TLTR', PartsCategoryEquip::TRAILER, 'Trailer Tires'],
        ]);

        $this->createTable('{{%violation_message}}', [
            'id' => $this->primaryKey(),
            'log_violation_warning_letter_text' => $this->text(),
            'log_violation_disciplinary_letter_text' => $this->text(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('{{%log_violation_code_code_pk}}', '{{%log_violation_code}}');
        $this->dropTable('{{%log_violation_code}}');
        $this->dropPrimaryKey('{{%vehicle_service_code_code_pk}}', '{{%vehicle_service_code}}');
        $this->dropTable('{{%vehicle_service_code}}');
        $this->dropPrimaryKey('{{%parts_category_code_pk}}', '{{%parts_category}}');
        $this->dropTable('{{%parts_category}}');
        $this->dropTable('{{%violation_message}}');
    }

}
