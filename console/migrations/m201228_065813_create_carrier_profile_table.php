<?php

use yii\db\Migration;

/**
 * Class m201228_065813_carrier_profile_tab
 */
class m201228_065813_create_carrier_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%carrier_type}}', [
            'code' => $this->string()->notNull(),
            'description' => $this->string(),
        ]);
        $this->addPrimaryKey(
            '{{%carrier_type_code_pk}}',
            '{{%carrier_type}}',
            'code'
        );
        $this->batchInsert('{{%carrier_type}}', ['code', 'description'], [
            ['HS1', 'Local Hot Shot'],
            ['HS2', 'OTR Hot Shot'],
            ['LL', 'LTL Local'],
            ['LR', 'LTL Over The Road'],
            ['MFL', 'Mixed Fleet Local'],
            ['MFR', 'Mixed Fleet OTR'],
            ['ST', '48 State Truckload']
        ]);

        $this->createTable('{{%carrier_ranking}}', [
            'code' => $this->string()->notNull(),
            'order' => $this->integer()->notNull(),
            'description' => $this->string(),
        ]);
        $this->addPrimaryKey(
            '{{%carrier_ranking_code_pk}}',
            '{{%carrier_ranking}}',
            'code'
        );

        $this->createTable('{{%carrier_profile}}', [
            'id' => $this->primaryKey(),
            'carrier_id' => $this->integer()->notNull(),
            'mcid' => $this->string(),
            'dotid' => $this->string(),
            'scac' => $this->string(),
            'type' => $this->string()->notNull(),
            'ranking' => $this->string(),
            'carb_id' => $this->string(),
            'approved' => $this->boolean()->notNull(),
            'contract_on_fire' => $this->boolean()->notNull(),
            'contract_date' => $this->date(),
            'authority' => $this->string(),
            // Insurance Policies
            'liability_ins_policy_no' => $this->string(),
            'liability_ins_expires' => $this->date(),
            'liability_ins_amount' => $this->decimal(),
            'cargo_ins_policy_no' => $this->string(),
            'cargo_ins_expires' => $this->date(),
            'cargo_ins_amount' => $this->decimal(),
            'trailer_inter_policy_no' => $this->string(),
            'trailer_inter_expires' => $this->date(),
            'work_comp_policy_no' => $this->string(),
            'work_comp_expires' => $this->date(),
            // Lane Preferences & Fleet Description
            'trucks_count' => $this->integer(),
            'trucks_type_1' => $this->string(),
            'trucks_type_2' => $this->string(),
            'trailers_count' => $this->integer(),
            'trailers_type_1' => $this->string(),
            'trailers_type_2' => $this->string(),
            'o_o' => $this->boolean()->notNull(),
            'haz_mat' => $this->boolean()->notNull(),
        ]);
        $this->addForeignKey(
            '{{%carrier_profile_carrier_id_fk}}',
            '{{%carrier_profile}}',
            'carrier_id',
            '{{%carrier}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%carrier_profile_type_fk}}',
            '{{%carrier_profile}}',
            'type',
            '{{%carrier_type}}',
            'code',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%carrier_profile_ranking_fk}}',
            '{{%carrier_profile}}',
            'ranking',
            '{{%carrier_ranking}}',
            'code',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%carrier_profile_trucks_type_1_fk}}',
            '{{%carrier_profile}}',
            'trucks_type_1',
            '{{%truck_type}}',
            'type',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%carrier_profile_trucks_type_2_fk}}',
            '{{%carrier_profile}}',
            'trucks_type_2',
            '{{%truck_type}}',
            'type',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%carrier_profile_trailers_type_1_fk}}',
            '{{%carrier_profile}}',
            'trailers_type_1',
            '{{%trailer_type}}',
            'type',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%carrier_profile_trailers_type_2_fk}}',
            '{{%carrier_profile}}',
            'trailers_type_2',
            '{{%trailer_type}}',
            'type',
            'RESTRICT',
            'CASCADE'
        );

        $this->createTable('{{%lane_preference}}', [
            'id' => $this->primaryKey(),
            'carrier_id' => $this->integer(),
            'forty_eight_states' => $this->boolean()->notNull(),
            'state_id' => $this->integer()
        ]);
        $this->addForeignKey(
            '{{%lane_preference_carrier_id_fk}}',
            '{{%lane_preference}}',
            'carrier_id',
            '{{%carrier}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%lane_preference_state_id_fk}}',
            '{{%lane_preference}}',
            'state_id',
            '{{%state}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createTable('{{%lane}}', [
            'id' => $this->primaryKey(),
            'preference_id' => $this->integer()->notNull(),
            'origin_city' => $this->string(),
            'origin_state_id' => $this->integer(),
            'origin_zone' => $this->string(),
            'destination_city' => $this->string(),
            'destination_state_id' => $this->integer(),
            'destination_zone' => $this->string(),
        ]);
        $this->addForeignKey(
            '{{%lane_preference_id_fk}}',
            '{{%lane}}',
            'preference_id',
            '{{%lane_preference}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%lane_origin_state_id_fk}}',
            '{{%lane}}',
            'origin_state_id',
            '{{%state}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%lane_origin_zone_fk}}',
            '{{%lane}}',
            'origin_zone',
            '{{%zone}}',
            'code',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%lane_destination_state_id_fk}}',
            '{{%lane}}',
            'destination_state_id',
            '{{%state}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%lane_destination_zone_fk}}',
            '{{%lane}}',
            'destination_zone',
            '{{%zone}}',
            'code',
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
            '{{%lane_preference_id_fk}}',
            '{{%lane}}'
        );
        $this->dropForeignKey(
            '{{%lane_origin_state_id_fk}}',
            '{{%lane}}'
        );
        $this->dropForeignKey(
            '{{%lane_origin_zone_fk}}',
            '{{%lane}}'
        );
        $this->dropForeignKey(
            '{{%lane_destination_state_id_fk}}',
            '{{%lane}}'
        );
        $this->dropForeignKey(
            '{{%lane_destination_zone_fk}}',
            '{{%lane}}'
        );
        $this->dropTable('{{%lane}}');

        $this->dropForeignKey(
            '{{%lane_preference_carrier_id_fk}}',
            '{{%lane_preference}}'
        );
        $this->dropForeignKey(
            '{{%lane_preference_state_id_fk}}',
            '{{%lane_preference}}'
        );
        $this->dropTable('{{%lane_preference}}');

        $this->dropForeignKey(
            '{{%carrier_profile_carrier_id_fk}}',
            '{{%carrier_profile}}'
        );
        $this->dropForeignKey(
            '{{%carrier_profile_type_fk}}',
            '{{%carrier_profile}}'
        );
        $this->dropForeignKey(
            '{{%carrier_profile_ranking_fk}}',
            '{{%carrier_profile}}'
        );
        $this->dropForeignKey(
            '{{%carrier_profile_trucks_type_1_fk}}',
            '{{%carrier_profile}}'
        );
        $this->dropForeignKey(
            '{{%carrier_profile_trucks_type_2_fk}}',
            '{{%carrier_profile}}'
        );
        $this->dropForeignKey(
            '{{%carrier_profile_trailers_type_1_fk}}',
            '{{%carrier_profile}}'
        );
        $this->dropForeignKey(
            '{{%carrier_profile_trailers_type_2_fk}}',
            '{{%carrier_profile}}'
        );
        $this->dropTable('{{%carrier_profile}}');

        $this->dropPrimaryKey(
            '{{%carrier_type_code_pk}}',
            '{{%carrier_type}}'
        );
        $this->dropTable('{{%carrier_type}}');

        $this->dropPrimaryKey(
            '{{%carrier_ranking_code_pk}}',
            '{{%carrier_ranking}}'
        );
        $this->dropTable('{{%carrier_ranking}}');
    }

}
