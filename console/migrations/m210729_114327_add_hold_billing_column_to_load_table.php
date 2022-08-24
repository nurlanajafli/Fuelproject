<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%load}}`.
 */
class m210729_114327_add_hold_billing_column_to_load_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%load}}', 'hold_billing', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%load}}', 'hold_billing');
    }
}
