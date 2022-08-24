<?php

use yii\db\Migration;

/**
 * Class m211029_064816_load_drop_bill_miles
 */
class m211029_064816_load_drop_bill_miles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%load}}', 'bill_miles');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%load}}', 'bill_miles', $this->integer());
    }
}
