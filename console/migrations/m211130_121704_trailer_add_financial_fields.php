<?php

use yii\db\Migration;

/**
 * Class m211130_121704_trailer_add_financial_fields
 */
class m211130_121704_trailer_add_financial_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%trailer}}', 'purchased_new', $this->boolean());
        $this->addColumn('{{%trailer}}', 'purchase_date', $this->date());
        $this->addColumn('{{%trailer}}', 'purchase_price', $this->decimal(10, 2));
        $this->addColumn('{{%trailer}}', 'insured', $this->boolean());
        $this->addColumn('{{%trailer}}', 'insured_date', $this->date());
        $this->addColumn('{{%trailer}}', 'insured_value', $this->decimal(10, 2));
        $this->addColumn('{{%trailer}}', 'annual_premium', $this->decimal(10, 2));
        $this->addColumn('{{%trailer}}', 'depreciated_value', $this->decimal(10, 2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%trailer}}', 'purchased_new');
        $this->dropColumn('{{%trailer}}', 'purchase_date');
        $this->dropColumn('{{%trailer}}', 'purchase_price');
        $this->dropColumn('{{%trailer}}', 'insured');
        $this->dropColumn('{{%trailer}}', 'insured_date');
        $this->dropColumn('{{%trailer}}', 'insured_value');
        $this->dropColumn('{{%trailer}}', 'annual_premium');
        $this->dropColumn('{{%trailer}}', 'depreciated_value');
    }
}
