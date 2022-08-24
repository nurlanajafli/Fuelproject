<?php

use yii\db\Migration;

/**
 * Class m210317_120557_driver_alter_dispatch_pay_decimal_columns
 */
class m210317_120557_driver_alter_dispatch_pay_decimal_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn("{{%driver}}", 'loaded_per_mile', $this->decimal(10, 2));
        $this->alterColumn("{{%driver}}", 'empty_per_mile', $this->decimal(10, 2));
        $this->alterColumn("{{%driver}}", 'percentage', $this->decimal(10, 2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn("{{%driver}}", 'loaded_per_mile', $this->decimal());
        $this->alterColumn("{{%driver}}", 'empty_per_mile', $this->decimal());
        $this->alterColumn("{{%driver}}", 'percentage', $this->decimal());
    }
}
