<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%driver_fuel_card}}`.
 */
class m210120_063854_create_driver_fuel_card_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $baseTable = 'driver_fuel_card';
        $this->createTable("{{%$baseTable}}", [
            'id' => $this->primaryKey(),
            'driver_id' => $this->integer()->notNull(),
            'card_type' => $this->string()->notNull(),
            'card_id' => $this->string()->notNull(),
            'discount_recapture_pct' => $this->decimal()->notNull()->defaultValue(0),
            'discount_recapture_gl_acct' => $this->string()->notNull(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            "{{%{$baseTable}_driver_id_fk}}",
            "{{%$baseTable}}",
            'driver_id',
            "{{%driver}}",
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseTable}_discount_recapture_gl_acct_fk}}",
            "{{%$baseTable}}",
            'discount_recapture_gl_acct',
            "{{%account}}",
            'account',
            'RESTRICT',
            'CASCADE'
        );
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
        $this->createIndex("{{%{$baseTable}_driver_id_card_type_unique}}", "{{%$baseTable}}", ['driver_id', 'card_type'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $baseTable = 'driver_fuel_card';
        $this->dropForeignKey(
            "{{%{$baseTable}_driver_id_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropForeignKey(
            "{{%{$baseTable}_discount_recapture_gl_acct_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropForeignKey(
            "{{%{$baseTable}_updated_by_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropForeignKey(
            "{{%{$baseTable}_created_by_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropIndex("{{%{$baseTable}_driver_id_card_type_unique}}", "{{%$baseTable}}");
        $this->dropTable("{{%{$baseTable}}}");
    }
}
