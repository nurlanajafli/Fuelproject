<?php

use yii\db\Migration;

/**
 * Class m210930_125739_load_rating_matrix
 */
class m210930_125739_load_rating_matrix extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%load_rating_matrix}}', [
            'number' => $this->string(5)->notNull(),
            'name' => $this->string(64)->notNull(),
            'rate_method' => $this->string(10)->notNull(),
            'rate_type' => $this->string(10)->notNull(),
            'loaded_and_empty' => $this->boolean()->defaultValue(false)->comment('for Distance only'),
            "inactive" => $this->boolean()->defaultValue(false),
        ]);
        $this->addPrimaryKey("{{%load_rating_matrix_pk}}", "{{%load_rating_matrix}}", "number");

        // ZipZip
        $this->createTable('{{%load_rating_zipzip}}', [
            'id' => $this->primaryKey(),
            'matrix' => $this->string(5)->notNull(),
            'zip_1_start' => $this->string(12)->notNull(),
            'zip_1_end' => $this->string(12)->notNull(),
            'zip_2_start' => $this->string(12)->notNull(),
            'zip_2_end' => $this->string(12)->notNull(),
            'low_wgt' => $this->integer()->comment('CWT'),
            'high_wgt' => $this->integer()->comment('CWT'),
            'bill_miles' => $this->integer()->comment('FLAT,MILES'),
            'rate' => $this->decimal(10,4)->comment('CWT,FLAT,MILES,POUND,PIECE,SPACE,TON,MULTI'),
            'base_rate' => $this->decimal(10,4)->comment('MILES'),
            'base_miles' => $this->integer()->comment('MILES'),
            'min' => $this->decimal(10,2)->comment('MILES,POUND'),
            'trl_type' => $this->string()->comment('MILES'),
            'pieces' => $this->decimal(10,4)->comment('LOT'),
            'description' => $this->string()
        ]);
        $this->addForeignKey(
            'fk_load_rating_zipzip_matrix_number',
            '{{%load_rating_zipzip}}',
            'matrix',
            '{{%load_rating_matrix}}',
            'number',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_load_rating_zipzip_trl_type',
            '{{%load_rating_zipzip}}',
            'trl_type',
            '{{%trailer_type}}',
            'type'
        );

        // ZoneZone
        $this->createTable('{{%load_rating_zonezone}}', [
            'id' => $this->primaryKey(),
            'matrix' => $this->string(5)->notNull(),
            'zone_1' => $this->string()->notNull(),
            'zone_2' => $this->string()->notNull(),
            'low_wgt' => $this->integer()->comment('CWT'),
            'high_wgt' => $this->integer()->comment('CWT'),
            'max_pcs' => $this->decimal(10,4)->comment('STEP'),
            'max_space' => $this->integer(),
            'max_wgt' => $this->integer(),
            'rate' => $this->decimal(10,4)->comment('CWT,FLAT,MILES,LOT,POUND,PIECE,SPACE,TON'),
            'base_rate' => $this->decimal(10,4)->comment('MILES'),
            'base_miles' => $this->integer()->comment('MILES'),
            'min' => $this->decimal(10,2)->comment('MILES,POUND'),
            'bill_miles' => $this->integer()->comment('FLAT,MILES'),
            'trl_type' => $this->string()->comment('MILES'),
            'pieces' => $this->decimal(10,4)->comment('LOT'),
            'description' => $this->string()
        ]);
        $this->addForeignKey(
            'fk_load_rating_zonezone_matrix_number',
            '{{%load_rating_zonezone}}',
            'matrix',
            '{{%load_rating_matrix}}',
            'number',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_load_rating_zonezone_zone1',
            '{{%load_rating_zonezone}}',
            'zone_1',
            '{{%zone}}',
            'code'
        );
        $this->addForeignKey(
            'fk_load_rating_zonezone_zone2',
            '{{%load_rating_zonezone}}',
            'zone_2',
            '{{%zone}}',
            'code'
        );
        $this->addForeignKey(
            'fk_load_rating_zonezone_trl_type',
            '{{%load_rating_zonezone}}',
            'trl_type',
            '{{%trailer_type}}',
            'type'
        );

        // Geograph
        $this->createTable('{{%load_rating_geograph}}', [
            'id' => $this->primaryKey(),
            'matrix' => $this->string(5)->notNull(),
            'origin_city' => $this->string(),
            'origin_state' => $this->integer()->notNull(),
            'dest_city' => $this->string(),
            'dest_state' => $this->integer()->notNull(),
            'low_wgt' => $this->integer()->comment('CWT'),
            'high_wgt' => $this->integer()->comment('CWT'),
            'bill_miles' => $this->integer()->comment('FLAT,MILES'),
            'rate' => $this->decimal(10,4)->comment('CWT,FLAT,MILES,POUND,PIECE,SPACE,TON,MULTI'),
            'base_rate' => $this->decimal(10,4)->comment('MILES'),
            'base_miles' => $this->integer()->comment('MILES'),
            'min' => $this->decimal(10,2)->comment('MILES,POUND'),
            'trl_type' => $this->string()->comment('MILES'),
            'pieces' => $this->decimal(10,4)->comment('LOT'),
            'description' => $this->string()
        ]);
        $this->addForeignKey(
            'fk_load_rating_geograph_matrix_number',
            '{{%load_rating_geograph}}',
            'matrix',
            '{{%load_rating_matrix}}',
            'number',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_load_rating_geograph_origin_state',
            '{{%load_rating_geograph}}',
            'origin_state',
            '{{%state}}',
            'id'
        );
        $this->addForeignKey(
            'fk_load_rating_geograph_dest_state',
            '{{%load_rating_geograph}}',
            'dest_state',
            '{{%state}}',
            'id'
        );
        $this->addForeignKey(
            'fk_load_rating_geograph_trl_type',
            '{{%load_rating_geograph}}',
            'trl_type',
            '{{%trailer_type}}',
            'type'
        );

        // Distance
        $this->createTable('{{%load_rating_distance}}', [
            'id' => $this->primaryKey(),
            'matrix' => $this->string(5)->notNull(),
            'low_miles' => $this->integer()->notNull(),
            'high_miles' => $this->integer()->notNull(),
            'rate' => $this->decimal(10,4)->comment('FLAT,MILES,TON'),
            'description' => $this->string()
        ]);
        $this->addForeignKey(
            'fk_load_rating_distance_matrix_number',
            '{{%load_rating_distance}}',
            'matrix',
            '{{%load_rating_matrix}}',
            'number',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%load_rating_distance}}');
        $this->dropTable('{{%load_rating_geograph}}');
        $this->dropTable('{{%load_rating_zonezone}}');
        $this->dropTable('{{%load_rating_zipzip}}');
        $this->dropTable('{{%load_rating_matrix}}');
    }
}
