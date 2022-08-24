<?php

use yii\db\Migration;

/**
 * Class m210119_125320_driver_add_dispatch_pay_columns
 */
class m210119_125320_driver_add_dispatch_pay_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $baseTable = 'driver';
        $this->addColumn("{{%$baseTable}}", 'pay_source', $this->string());
        $this->addColumn("{{%$baseTable}}", 'loaded_miles', $this->string());
        $this->addColumn("{{%$baseTable}}", 'empty_miles', $this->string());
        $this->addColumn("{{%$baseTable}}", 'loaded_pay_type', $this->string());
        $this->addColumn("{{%$baseTable}}", 'loaded_per_mile', $this->decimal());
        $this->addColumn("{{%$baseTable}}", 'empty_per_mile', $this->decimal());
        $this->addColumn("{{%$baseTable}}", 'percentage', $this->decimal());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $baseTable = 'driver';
        $this->dropColumn("{{%$baseTable}}", 'pay_source');
        $this->dropColumn("{{%$baseTable}}", 'loaded_miles');
        $this->dropColumn("{{%$baseTable}}", 'empty_miles');
        $this->dropColumn("{{%$baseTable}}", 'loaded_pay_type');
        $this->dropColumn("{{%$baseTable}}", 'loaded_per_mile');
        $this->dropColumn("{{%$baseTable}}", 'empty_per_mile');
        $this->dropColumn("{{%$baseTable}}", 'percentage');
    }
}
