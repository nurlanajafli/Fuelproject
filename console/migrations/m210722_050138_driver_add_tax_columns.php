<?php

use yii\db\Migration;

/**
 * Class m210722_050138_driver_add_tax_columns
 */
class m210722_050138_driver_add_tax_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%driver}}', 'pay_frequency', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%driver}}', 'pay_frequency');
    }
}
