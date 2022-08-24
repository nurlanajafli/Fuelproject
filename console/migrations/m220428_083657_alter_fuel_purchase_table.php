<?php

use yii\db\Migration;

/**
 * Class m220428_083657_alter_fuel_purchase_table
 */
class m220428_083657_alter_fuel_purchase_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%fuel_purchase}}', 'transaction_id', $this->string());
        $this->addColumn('{{%fuel_purchase}}', 'card', $this->string());
        $this->addColumn('{{%fuel_purchase}}', 'product_code', $this->integer());
        $this->createIndex(
            'idx_fuel_purchase_transaction_id',
            '{{%fuel_purchase}}',
            'transaction_id'
        );
        $this->addForeignKey(
            'fk_fuel_purchase_product_code_fuel_product_codes_id',
            '{{%fuel_purchase}}',
            'product_code',
            '{{%fuel_product_codes}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_fuel_purchase_product_code_fuel_product_codes_id',
            '{{%fuel_purchase}}'
        );
        $this->dropIndex('idx_fuel_purchase_transaction_id', '{{%fuel_purchase}}');
        $this->dropColumn('{{%fuel_purchase}}', 'product_code');
        $this->dropColumn('{{%fuel_purchase}}', 'card');
        $this->dropColumn('{{%fuel_purchase}}', 'transaction_id');
    }
}
