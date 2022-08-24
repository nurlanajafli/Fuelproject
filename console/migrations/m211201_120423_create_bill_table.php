<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bill}}`.
 */
class m211201_120423_create_bill_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // amount, balance
        $this->createTable('{{%bill}}', [
            'id' => $this->primaryKey(),
            'from_vendor_id' => $this->integer(),
            'from_carrier_id' => $this->integer(),
            'bill_no' => $this->string(),
            'posting_date' => $this->date(),
            'bill_date' => $this->date(),
            'due_date' => $this->date(),
            'office_id' => $this->integer(),
            'ap_account' => $this->string(),
            'memo' => $this->string(),
            'transaction_id' => $this->string(),
            'payment_terms' => $this->integer(),
            'status' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'created_by' => $this->integer(),
            'updated_at' => $this->timestamp(),
            'updated_by' => $this->integer()
        ]);
        $this->addForeignKey(
            '{{%bill_office_id_fk}}',
            '{{%bill}}',
            'office_id',
            '{{%office}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%bill_ap_account_fk}}',
            '{{%bill}}',
            'ap_account',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%bill_created_by_fk}}',
            '{{%bill}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%bill_updated_by_fk}}',
            '{{%bill}}',
            'updated_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%bill_from_vendor_id_fk}}',
            '{{%bill}}',
            'from_vendor_id',
            '{{%vendor}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%bill_from_carrier_id_fk}}',
            '{{%bill}}',
            'from_carrier_id',
            '{{%carrier}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%bill_payment_terms_fk}}',
            '{{%bill}}',
            'payment_terms',
            '{{%payment_term_code}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createTable('{{%bill_item}}', [
            'id' => $this->primaryKey(),
            'bill_id' => $this->integer()->notNull(),
            'driver_id' => $this->integer()->notNull(),
            'account' => $this->string(),
            'amount' => $this->decimal(10, 2),
            'special' => $this->string(),
            'memo' => $this->string(),
            'created_at' => $this->timestamp()->notNull(),
            'created_by' => $this->integer(),
            'updated_at' => $this->timestamp(),
            'updated_by' => $this->integer()
        ]);
        $this->addForeignKey(
            '{{%bill_item_bill_id_fk}}',
            '{{%bill_item}}',
            'bill_id',
            '{{%bill}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%bill_item_driver_id_fk}}',
            '{{%bill_item}}',
            'driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%bill_item_account_fk}}',
            '{{%bill_item}}',
            'account',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%bill_item_created_by_fk}}',
            '{{%bill_item}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%bill_item_updated_by_fk}}',
            '{{%bill_item}}',
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
            '{{%bill_item_bill_id_fk}}',
            '{{%bill_item}}'
        );
        $this->dropForeignKey(
            '{{%bill_item_driver_id_fk}}',
            '{{%bill_item}}'
        );
        $this->dropForeignKey(
            '{{%bill_item_account_fk}}',
            '{{%bill_item}}'
        );
        $this->dropForeignKey(
            '{{%bill_item_created_by_fk}}',
            '{{%bill_item}}'
        );
        $this->dropForeignKey(
            '{{%bill_item_updated_by_fk}}',
            '{{%bill_item}}'
        );
        $this->dropForeignKey(
            '{{%bill_payment_terms_fk}}',
            '{{%bill}}'
        );
        $this->dropTable('{{%bill_item}}');

        $this->dropForeignKey(
            '{{%bill_office_id_fk}}',
            '{{%bill}}'
        );
        $this->dropForeignKey(
            '{{%bill_ap_account_fk}}',
            '{{%bill}}'
        );
        $this->dropForeignKey(
            '{{%bill_created_by_fk}}',
            '{{%bill}}'
        );
        $this->dropForeignKey(
            '{{%bill_updated_by_fk}}',
            '{{%bill}}'
        );
        $this->dropForeignKey(
            '{{%bill_from_vendor_id_fk}}',
            '{{%bill}}'
        );
        $this->dropForeignKey(
            '{{%bill_from_carrier_id_fk}}',
            '{{%bill}}'
        );
        $this->dropTable('{{%bill}}');
    }
}
