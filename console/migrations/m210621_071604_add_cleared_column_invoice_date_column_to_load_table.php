<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%load}}`.
 */
class m210621_071604_add_cleared_column_invoice_date_column_to_load_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%load}}', 'cleared', $this->boolean());
        $this->addColumn('{{%load}}', 'invoice_date', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%load}}', 'cleared');
        $this->dropColumn('{{%load}}', 'invoice_date');
    }
}
