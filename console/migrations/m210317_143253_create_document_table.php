<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%document}}`.
 */
class m210317_143253_create_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $baseTable = 'document';
        $t = "{{%$baseTable}}";
        $this->createTable($t, [
            'id' => $this->primaryKey(),
            'driver_id' => $this->integer(),
            'truck_id' => $this->integer(),
            'trailer_id' => $this->integer(),
            'carrier_id' => $this->integer(),
            'customer_id' => $this->integer(),
            'location_id' => $this->integer(),
            'vendor_id' => $this->integer(),
            'description' => $this->string()->notNull(),
            'mime_type' => $this->string()->notNull(),
            'file_name' => $this->string()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => 'timestamp with time zone',
        ]);
        $this->addForeignKey(
            "{{%{$baseTable}_created_by_fk}}",
            $t,
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseTable}_driver_id_fk}}",
            $t,
            'driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseTable}_truck_id_fk}}",
            $t,
            'truck_id',
            '{{%truck}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseTable}_trailer_id_fk}}",
            $t,
            'trailer_id',
            '{{%trailer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseTable}_carrier_id_fk}}",
            $t,
            'carrier_id',
            '{{%carrier}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseTable}_customer_id_fk}}",
            $t,
            'customer_id',
            '{{%customer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseTable}_location_id_fk}}",
            $t,
            'location_id',
            '{{%location}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseTable}_vendor_id_fk}}",
            $t,
            'vendor_id',
            '{{%vendor}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->dropForeignKey(
            '{{%driver_image_driver_id_fk}}',
            '{{%driver_image}}'
        );
        $this->dropTable('{{%driver_image}}');
        $this->dropForeignKey(
            '{{%truck_image_truck_id_fk}}',
            '{{%truck_image}}'
        );
        $this->dropTable('{{%truck_image}}');
        $this->dropForeignKey(
            '{{%trailer_image_trailer_id_fk}}',
            '{{%trailer_image}}'
        );
        $this->dropTable('{{%trailer_image}}');
        $this->dropForeignKey(
            '{{%carrier_image_carrier_id_fk}}',
            '{{%carrier_image}}'
        );
        $this->dropTable('{{%carrier_image}}');
        $this->dropForeignKey(
            '{{%customer_image_customer_id_fk}}',
            '{{%customer_image}}'
        );
        $this->dropTable('{{%customer_image}}');
        $this->dropForeignKey(
            '{{%location_image_location_id_fk}}',
            '{{%location_image}}'
        );
        $this->dropTable('{{%location_image}}');
        $this->dropForeignKey(
            '{{%vendor_image_vendor_id_fk}}',
            '{{%vendor_image}}'
        );
        $this->dropTable('{{%vendor_image}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $baseTable = 'document';
        $t = "{{%$baseTable}}";
        $this->dropForeignKey(
            "{{%{$baseTable}_created_by_fk}}",
            $t
        );
        $this->dropForeignKey(
            "{{%{$baseTable}_driver_id_fk}}",
            $t
        );
        $this->dropForeignKey(
            "{{%{$baseTable}_truck_id_fk}}",
            $t
        );
        $this->dropForeignKey(
            "{{%{$baseTable}_trailer_id_fk}}",
            $t
        );
        $this->dropForeignKey(
            "{{%{$baseTable}_carrier_id_fk}}",
            $t
        );
        $this->dropForeignKey(
            "{{%{$baseTable}_customer_id_fk}}",
            $t
        );
        $this->dropForeignKey(
            "{{%{$baseTable}_location_id_fk}}",
            $t
        );
        $this->dropForeignKey(
            "{{%{$baseTable}_vendor_id_fk}}",
            $t
        );
        $this->dropTable($t);

        $this->createTable('{{%driver_image}}', [
            'id' => $this->primaryKey(),
            'driver_id' => $this->integer()->notNull(),
            'description' => $this->string(),
            'path' => $this->string()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%driver_image_driver_id_fk}}',
            '{{%driver_image}}',
            'driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->createTable('{{%truck_image}}', [
            'id' => $this->primaryKey(),
            'truck_id' => $this->integer()->notNull(),
            'description' => $this->string(),
            'path' => $this->string()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%truck_image_truck_id_fk}}',
            '{{%truck_image}}',
            'truck_id',
            '{{%truck}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->createTable('{{%trailer_image}}', [
            'id' => $this->primaryKey(),
            'trailer_id' => $this->integer()->notNull(),
            'description' => $this->string(),
            'path' => $this->string()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%trailer_image_trailer_id_fk}}',
            '{{%trailer_image}}',
            'trailer_id',
            '{{%trailer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->createTable('{{%carrier_image}}', [
            'id' => $this->primaryKey(),
            'carrier_id' => $this->integer()->notNull(),
            'description' => $this->string(),
            'path' => $this->string()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%carrier_image_carrier_id_fk}}',
            '{{%carrier_image}}',
            'carrier_id',
            '{{%carrier}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->createTable('{{%customer_image}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer()->notNull(),
            'description' => $this->string(),
            'path' => $this->string()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%customer_image_customer_id_fk}}',
            '{{%customer_image}}',
            'customer_id',
            '{{%customer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->createTable('{{%location_image}}', [
            'id' => $this->primaryKey(),
            'location_id' => $this->integer()->notNull(),
            'description' => $this->string(),
            'path' => $this->string()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%location_image_location_id_fk}}',
            '{{%location_image}}',
            'location_id',
            '{{%location}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->createTable('{{%vendor_image}}', [
            'id' => $this->primaryKey(),
            'vendor_id' => $this->integer()->notNull(),
            'description' => $this->string(),
            'path' => $this->string()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%vendor_image_vendor_id_fk}}',
            '{{%vendor_image}}',
            'vendor_id',
            '{{%vendor}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }
}
