<?php

use yii\db\Migration;

/**
 * Class m220208_092509_add_new_column_to_driver
 */
class m220208_092509_add_new_column_to_driver extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('driver', 'co_driver_earning_percent', $this->decimal(10,2)->defaultValue(0.5));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('driver', 'new_column_name');
    }

}
