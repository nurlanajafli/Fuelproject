<?php

use yii\db\Migration;

/**
 * Class m201127_114735_truck_stage_1
 */
class m201127_114735_truck_stage_1 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%truck}}', 'lat');
        $this->dropColumn('{{%truck}}', 'lng');

        $this->addColumn('{{%truck}}', 'type', $this->string()->notNull());
        $this->addColumn('{{%truck}}', 'year', $this->integer()->notNull());
        $this->addColumn('{{%truck}}', 'make', $this->string()->notNull());
        $this->addColumn('{{%truck}}', 'model', $this->string()->notNull());
        $this->addColumn('{{%truck}}', 'tare', $this->integer()->notNull());
        $this->addColumn('{{%truck}}', 'in_svc', $this->date());
        $this->addColumn('{{%truck}}', 'serial', $this->string()->notNull());
        $this->addColumn('{{%truck}}', 'vin', $this->string()->notNull());
        $this->addColumn('{{%truck}}', 'status', $this->string()->notNull());
        $this->addColumn('{{%truck}}', 'license', $this->string()->notNull());
        $this->addColumn('{{%truck}}', 'license_state_id', $this->integer()->notNull());
        $this->addColumn('{{%truck}}', 'carb_id', $this->string());
        $this->addColumn('{{%truck}}', 'office_id', $this->integer());
        $this->addColumn('{{%truck}}', 'notes', $this->text());
        $this->addColumn('{{%truck}}', 'out_of_service', $this->date());
        $this->addColumn('{{%truck}}', 'updated_by', $this->integer());
        $this->addColumn('{{%truck}}', 'updated_at', $this->timestamp());
        $this->addColumn('{{%truck}}', 'created_by', $this->integer());
        $this->addColumn('{{%truck}}', 'created_at', $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'));
        $this->addForeignKey(
            '{{%truck_type_fk}}',
            '{{%truck}}',
            'type',
            '{{%truck_type}}',
            'type',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%truck_license_state_id_fk}}',
            '{{%truck}}',
            'license_state_id',
            '{{%state}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%truck_office_id_fk}}',
            '{{%truck}}',
            'office_id',
            '{{%office}}',
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
            '{{%truck_type_fk}}',
            '{{%truck}}'
        );
        $this->dropForeignKey(
            '{{%truck_license_state_id_fk}}',
            '{{%truck}}'
        );
        $this->dropForeignKey(
            '{{%truck_office_id_fk}}',
            '{{%truck}}'
        );
        $this->dropColumn('{{%truck}}', 'type');
        $this->dropColumn('{{%truck}}', 'year');
        $this->dropColumn('{{%truck}}', 'make');
        $this->dropColumn('{{%truck}}', 'model');
        $this->dropColumn('{{%truck}}', 'tare');
        $this->dropColumn('{{%truck}}', 'in_svc');
        $this->dropColumn('{{%truck}}', 'serial');
        $this->dropColumn('{{%truck}}', 'vin');
        $this->dropColumn('{{%truck}}', 'status');
        $this->dropColumn('{{%truck}}', 'license');
        $this->dropColumn('{{%truck}}', 'license_state_id');
        $this->dropColumn('{{%truck}}', 'carb_id');
        $this->dropColumn('{{%truck}}', 'office_id');
        $this->dropColumn('{{%truck}}', 'notes');
        $this->dropColumn('{{%truck}}', 'out_of_service');
        $this->dropColumn('{{%truck}}', 'updated_by');
        $this->dropColumn('{{%truck}}', 'updated_at');
        $this->dropColumn('{{%truck}}', 'created_by');
        $this->dropColumn('{{%truck}}', 'created_at');

        $this->addColumn('{{%truck}}', 'lat', $this->float());
        $this->addColumn('{{%truck}}', 'lng', $this->float());
    }

}
