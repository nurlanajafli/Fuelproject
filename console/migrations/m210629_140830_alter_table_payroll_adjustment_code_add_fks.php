<?php

use yii\db\Migration;

/**
 * Class m210629_140830_alter_table_payroll_adjustment_code_add_fks
 */
class m210629_140830_alter_table_payroll_adjustment_code_add_fks extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            '{{%payroll_adjustment_code_post_to_carrier_fk}}',
            '{{%payroll_adjustment_code}}',
            'post_to_carrier_id',
            '{{%carrier}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%payroll_adjustment_code_post_to_vendor_fk}}',
            '{{%payroll_adjustment_code}}',
            'post_to_vendor_id',
            '{{%vendor}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%payroll_adjustment_code_post_to_customer_fk}}',
            '{{%payroll_adjustment_code}}',
            'post_to_customer_id',
            '{{%customer}}',
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
        $this->dropForeignKey('{{%payroll_adjustment_code_post_to_customer_fk}}','{{%payroll_adjustment_code}}');
        $this->dropForeignKey('{{%payroll_adjustment_code_post_to_vendor_fk}}','{{%payroll_adjustment_code}}');
        $this->dropForeignKey('{{%payroll_adjustment_code_post_to_carrier_fk}}','{{%payroll_adjustment_code}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210629_140830_alter_table_payroll_adjustment_code_add_fks cannot be reverted.\n";

        return false;
    }
    */
}
