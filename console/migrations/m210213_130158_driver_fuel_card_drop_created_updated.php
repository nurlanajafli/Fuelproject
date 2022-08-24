<?php

use yii\db\Migration;

/**
 * Class m210213_130158_driver_fuel_card_drop_created_updated
 */
class m210213_130158_driver_fuel_card_drop_created_updated extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $baseTable = 'driver_fuel_card';
        $this->alterColumn("{{%$baseTable}}", 'card_id', $this->string());
        $this->alterColumn("{{%$baseTable}}", 'discount_recapture_pct', $this->decimal());
        $this->alterColumn("{{%$baseTable}}", 'discount_recapture_gl_acct', $this->string());
        $this->dropForeignKey(
            "{{%{$baseTable}_updated_by_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropForeignKey(
            "{{%{$baseTable}_created_by_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropColumn("{{%$baseTable}}", 'updated_by');
        $this->dropColumn("{{%$baseTable}}", 'updated_at');
        $this->dropColumn("{{%$baseTable}}", 'created_by');
        $this->dropColumn("{{%$baseTable}}", 'created_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $baseTable = 'driver_fuel_card';
        $this->alterColumn("{{%$baseTable}}", 'card_id', $this->string()->notNull());
        $this->alterColumn("{{%$baseTable}}", 'discount_recapture_pct', $this->decimal()->notNull()->defaultValue(0));
        $this->alterColumn("{{%$baseTable}}", 'discount_recapture_gl_acct', $this->string()->notNull());
        $this->addColumn("{{%$baseTable}}", 'updated_by', $this->integer());
        $this->addColumn("{{%$baseTable}}", 'updated_at', $this->timestamp());
        $this->addColumn("{{%$baseTable}}", 'created_by', $this->integer());
        $this->addColumn("{{%$baseTable}}", 'created_at', $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'));
        $this->addForeignKey(
            "{{%{$baseTable}_updated_by_fk}}",
            "{{%$baseTable}}",
            'updated_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseTable}_created_by_fk}}",
            "{{%$baseTable}}",
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }
}
