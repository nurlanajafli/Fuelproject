<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%service_code}}`.
 */
class m220412_105138_create_service_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%service_code}}', [
            'id' => $this->string(),
            'description' => $this->string(),
        ]);
        $this->addPrimaryKey('{{%service_code_id_pk}}', '{{%service_code}}', 'id');
        $this->batchInsert(
            '{{%service_code}}',
            ['id', 'description'],
            [
                ['AC', 'A/C'],
                ['ACCREP TRK', 'Accident Repair on a Truck'],
                ['ALIGN', 'Alignment'],
                ['BOB INS', 'Bobtail Insurance'],
                ['BRKSVC TRK', 'Brake Service Truck'],
                ['ELECT TRK', 'Electrical Truck'],
                ['ENGREPAIR', 'Engine Repair'],
                ['GENREP TRL', 'General Repair on a Truck'],
                ['GLASS TRK', 'Windshield Repair Truck'],
                ['OILCHANGE', 'Oil Change'],
                ['PERMITNY', 'NY Permit'],
                ['PL EXP TRK', 'Truck Plate Expiration'],
                ['PM SVC A', 'TRUCK PM SERVICE'],
                ['TIRES', 'Tires'],
                ['TRANSSVC', 'Transmission/Drive Train'],
                ['TRKINSPA', 'Annual DOT Inspection'],
                ['TRKINSPP', 'Periodic Inspection'],
                ['TRUCK WASH', 'TRUCK WASH'],
            ]
        );

        $this->renameTable('{{%work_order_item}}', '{{%work_order_service}}');
        $this->addColumn('{{%work_order_service}}', 'service_date', $this->date()->notNull());
        $this->addColumn('{{%work_order_service}}', 'service_code', $this->string());
        $this->addForeignKey('{{%work_order_service_code_fk}}', '{{%work_order_service}}', 'service_code', '{{%service_code}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addColumn('{{%work_order_service}}', 'vendor_id', $this->integer());
        $this->addForeignKey('{{%work_order_service_vendor_fk}}', '{{%work_order_service}}', 'vendor_id', '{{%vendor}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addColumn('{{%work_order_service}}', 'description', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%work_order_service_code_fk}}', '{{%work_order_service}}');
        $this->dropForeignKey('{{%work_order_service_vendor_fk}}', '{{%work_order_service}}');
        $this->dropColumn('{{%work_order_service}}', 'service_date');
        $this->dropColumn('{{%work_order_service}}', 'service_code');
        $this->dropColumn('{{%work_order_service}}', 'vendor_id');
        $this->dropColumn('{{%work_order_service}}', 'description');
        $this->renameTable('{{%work_order_service}}', '{{%work_order_item}}');

        $this->dropPrimaryKey('{{%service_code_id_pk}}', '{{%service_code}}');
        $this->dropTable('{{%service_code}}');
    }
}
