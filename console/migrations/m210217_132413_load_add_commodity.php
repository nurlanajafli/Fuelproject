<?php

use yii\db\Migration;

/**
 * Class m210217_132413_load_add_commodity
 */
class m210217_132413_load_add_commodity extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $baseTable = 'load';
        $colPrefix = 'commodity_';
        $this->addColumn("{{%$baseTable}}", "{$colPrefix}commodity_id", $this->integer());
        $this->addColumn("{{%$baseTable}}", "{$colPrefix}reference", $this->string());
        $this->addColumn("{{%$baseTable}}", "{$colPrefix}weight", $this->integer());
        $this->addColumn("{{%$baseTable}}", "{$colPrefix}pieces", $this->decimal());
        $this->addColumn("{{%$baseTable}}", "{$colPrefix}space", $this->decimal());
        $this->addForeignKey(
            "{{%{$baseTable}_{$colPrefix}commodity_id_fk}}",
            "{{%$baseTable}}",
            "{$colPrefix}commodity_id",
            "{{%commodity}}",
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
        $baseTable = 'load';
        $colPrefix = 'commodity_';
        $this->dropForeignKey(
            "{{%{$baseTable}_{$colPrefix}commodity_id_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropColumn("{{%$baseTable}}", "{$colPrefix}commodity_id");
        $this->dropColumn("{{%$baseTable}}", "{$colPrefix}reference");
        $this->dropColumn("{{%$baseTable}}", "{$colPrefix}weight");
        $this->dropColumn("{{%$baseTable}}", "{$colPrefix}pieces");
        $this->dropColumn("{{%$baseTable}}", "{$colPrefix}space");
    }
}
