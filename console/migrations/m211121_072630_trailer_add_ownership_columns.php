<?php

use yii\db\Migration;

/**
 * Class m211121_072630_trailer_add_ownership_columns
 */
class m211121_072630_trailer_add_ownership_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%trailer}}', 'company_owned_leased', $this->string());
        $this->addColumn('{{%trailer}}', 'owned_by_carrier_id', $this->integer());
        $this->addColumn('{{%trailer}}', 'owned_by_customer_id', $this->integer());
        $this->addColumn('{{%trailer}}', 'owned_by_driver_id', $this->integer());
        $this->addColumn('{{%trailer}}', 'owned_by_vendor_id', $this->integer());
        $this->addForeignKey(
            '{{%trailer__owned_by_carrier_fk}}',
            '{{%trailer}}',
            'owned_by_carrier_id',
            '{{%carrier}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%trailer__owned_by_customer_fk}}',
            '{{%trailer}}',
            'owned_by_customer_id',
            '{{%customer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%trailer__owned_by_driver_fk}}',
            '{{%trailer}}',
            'owned_by_driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%trailer__owned_by_vendor_fk}}',
            '{{%trailer}}',
            'owned_by_vendor_id',
            '{{%vendor}}',
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
            '{{%trailer__owned_by_carrier_fk}}',
            '{{%trailer}}'
        );
        $this->dropForeignKey(
            '{{%trailer__owned_by_customer_fk}}',
            '{{%trailer}}'
        );
        $this->dropForeignKey(
            '{{%trailer__owned_by_driver_fk}}',
            '{{%trailer}}'
        );
        $this->dropForeignKey(
            '{{%trailer__owned_by_vendor_fk}}',
            '{{%trailer}}'
        );
        $this->dropColumn('{{%trailer}}', 'company_owned_leased');
        $this->dropColumn('{{%trailer}}', 'owned_by_carrier_id');
        $this->dropColumn('{{%trailer}}', 'owned_by_customer_id');
        $this->dropColumn('{{%trailer}}', 'owned_by_driver_id');
        $this->dropColumn('{{%trailer}}', 'owned_by_vendor_id');
    }
}
