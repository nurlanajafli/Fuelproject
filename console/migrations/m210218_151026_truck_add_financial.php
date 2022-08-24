<?php

use yii\db\Migration;

/**
 * Class m210218_151026_truck_add_financial
 */
class m210218_151026_truck_add_financial extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $baseTable = 'truck';
        $this->addColumn("{{%$baseTable}}", 'company_owned_leased', $this->string());
        $tables = ['carrier', 'customer', 'vendor'];
        foreach ($tables as $table) {
            $this->addColumn("{{%$baseTable}}", $column = "owned_by_{$table}_id", $this->integer());
            $this->addForeignKey(
                "{{%{$baseTable}_{$column}_fk}}",
                "{{%$baseTable}}",
                $column,
                "{{%$table}}",
                'id',
                'RESTRICT',
                'CASCADE'
            );
        }
        $this->addColumn("{{%$baseTable}}", 'purchased_new', $this->boolean());
        $this->addColumn("{{%$baseTable}}", 'purchase_date', $this->date());
        $this->addColumn("{{%$baseTable}}", 'purchase_price', $this->decimal(10, 2));
        $this->addColumn("{{%$baseTable}}", 'non_ifta', $this->boolean());
        $this->addColumn("{{%$baseTable}}", 'ny_permit', $this->string());
        $this->addColumn("{{%$baseTable}}", 'ny_gross', $this->integer());
        $this->addColumn("{{%$baseTable}}", 'or_decl_wgt', $this->integer());
        $this->addColumn("{{%$baseTable}}", 'or_decl_axles', $this->string());
        $this->addColumn("{{%$baseTable}}", 'insured', $this->boolean());
        $this->addColumn("{{%$baseTable}}", 'insured_date', $this->date());
        $this->addColumn("{{%$baseTable}}", 'insured_value', $this->decimal(10, 2));
        $this->addColumn("{{%$baseTable}}", 'annual_premium', $this->decimal(10, 2));
        $this->addColumn("{{%$baseTable}}", 'depreciated_value', $this->decimal(10, 2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $baseTable = 'truck';
        $this->dropColumn("{{%$baseTable}}", 'company_owned_leased');
        $tables = ['carrier', 'customer', 'vendor'];
        foreach ($tables as $table) {
            $column = "owned_by_{$table}_id";
            $this->dropForeignKey(
                "{{%{$baseTable}_{$column}_fk}}",
                "{{%$baseTable}}"
            );
            $this->dropColumn("{{%$baseTable}}", $column);
        }
        $this->dropColumn("{{%$baseTable}}", 'purchased_new');
        $this->dropColumn("{{%$baseTable}}", 'purchase_date');
        $this->dropColumn("{{%$baseTable}}", 'purchase_price');
        $this->dropColumn("{{%$baseTable}}", 'non_ifta');
        $this->dropColumn("{{%$baseTable}}", 'ny_permit');
        $this->dropColumn("{{%$baseTable}}", 'ny_gross');
        $this->dropColumn("{{%$baseTable}}", 'or_decl_wgt');
        $this->dropColumn("{{%$baseTable}}", 'or_decl_axles');
        $this->dropColumn("{{%$baseTable}}", 'insured');
        $this->dropColumn("{{%$baseTable}}", 'insured_date');
        $this->dropColumn("{{%$baseTable}}", 'insured_value');
        $this->dropColumn("{{%$baseTable}}", 'annual_premium');
        $this->dropColumn("{{%$baseTable}}", 'depreciated_value');
    }
}
