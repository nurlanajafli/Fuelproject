<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%load}}`.
 */
class m210107_113524_create_load_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%load}}', [
            'id' => $this->primaryKey(),
            'booked_by' => $this->integer(),
            'bill' => $this->string(),
            'bill_to' => $this->integer(),
            'customer_reference' => $this->string(),
            'notes' => $this->text(),
            'bill_miles' => $this->integer(),
            'received' => $this->date(),
            'release' => $this->date(),
            'office_id' => $this->integer(),
            'type_id' => $this->integer(),
//            'team' => $this->integer(),
            'trailer_type' => $this->string(),
            'seal_no' => $this->string(),
            'salesman' => $this->integer(),
            'status' => $this->string(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%load_booked_by_fk}}',
            '{{%load}}',
            'booked_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_bill_to_fk}}',
            '{{%load}}',
            'bill_to',
            '{{%customer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_office_id_fk}}',
            '{{%load}}',
            'office_id',
            '{{%office}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_type_id_fk}}',
            '{{%load}}',
            'type_id',
            '{{%load_type}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_trailer_type_fk}}',
            '{{%load}}',
            'trailer_type',
            '{{%trailer_type}}',
            'type',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_created_by_fk}}',
            '{{%load}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%load_updated_by_fk}}',
            '{{%load}}',
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
            '{{%load_booked_by_fk}}',
            '{{%load}}'
        );
        $this->dropForeignKey(
            '{{%load_bill_to_fk}}',
            '{{%load}}'
        );
        $this->dropForeignKey(
            '{{%load_office_id_fk}}',
            '{{%load}}'
        );
        $this->dropForeignKey(
            '{{%load_type_id_fk}}',
            '{{%load}}'
        );
        $this->dropForeignKey(
            '{{%load_trailer_type_fk}}',
            '{{%load}}'
        );
        $this->dropForeignKey(
            '{{%load_created_by_fk}}',
            '{{%load}}'
        );
        $this->dropForeignKey(
            '{{%load_updated_by_fk}}',
            '{{%load}}'
        );
        $this->dropTable('{{%load}}');
    }
}
