<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%driver_adjustment}}`.
 */
class m210720_043449_create_driver_adjustment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $baseName = 'driver_adjustment';
        $this->createTable("{{%$baseName}}", [
            'id' => $this->primaryKey(),
            'driver_id' => $this->integer()->notNull(),
            'payroll_adjustment_code' => $this->string(),
            'post_to_carrier_id' => $this->integer(),
            'post_to_driver_id' => $this->integer(),
            'post_to_vendor_id' => $this->integer(),
            'account' => $this->string(),
            'calc_by' => $this->string(),
            'amount' => $this->decimal(10, 2),
            'cap_id' => $this->integer(),
            'truck_id' => $this->integer(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
        $this->addForeignKey(
            "{{%{$baseName}_driver_id_fk}}",
            "{{%$baseName}}",
            'driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseName}_adj_code_fk}}",
            "{{%$baseName}}",
            'payroll_adjustment_code',
            '{{%payroll_adjustment_code}}',
            'code',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseName}_post_to_carrier_id_fk}}",
            "{{%$baseName}}",
            'post_to_carrier_id',
            '{{%carrier}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseName}_post_to_driver_id_fk}}",
            "{{%$baseName}}",
            'post_to_driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseName}_post_to_vendor_id_fk}}",
            "{{%$baseName}}",
            'post_to_vendor_id',
            '{{%vendor}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseName}_account_fk}}",
            "{{%$baseName}}",
            'account',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseName}_cap_id_fk}}",
            "{{%$baseName}}",
            'cap_id',
            '{{%cap}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseName}_truck_id_fk}}",
            "{{%$baseName}}",
            'truck_id',
            '{{%truck}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseName}_created_by_fk}}",
            "{{%$baseName}}",
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseName}_updated_by_fk}}",
            "{{%$baseName}}",
            'updated_by',
            '{{%user}}',
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
        $baseName = 'driver_adjustment';
        $this->dropForeignKey(
            "{{%{$baseName}_driver_id_fk}}",
            "{{%$baseName}}"
        );
        $this->dropForeignKey(
            "{{%{$baseName}_adj_code_fk}}",
            "{{%$baseName}}"
        );
        $this->dropForeignKey(
            "{{%{$baseName}_post_to_carrier_id_fk}}",
            "{{%$baseName}}"
        );
        $this->dropForeignKey(
            "{{%{$baseName}_post_to_driver_id_fk}}",
            "{{%$baseName}}"
        );
        $this->dropForeignKey(
            "{{%{$baseName}_post_to_vendor_id_fk}}",
            "{{%$baseName}}"
        );
        $this->dropForeignKey(
            "{{%{$baseName}_account_fk}}",
            "{{%$baseName}}"
        );
        $this->dropForeignKey(
            "{{%{$baseName}_cap_id_fk}}",
            "{{%$baseName}}"
        );
        $this->dropForeignKey(
            "{{%{$baseName}_truck_id_fk}}",
            "{{%$baseName}}"
        );
        $this->dropForeignKey(
            "{{%{$baseName}_created_by_fk}}",
            "{{%$baseName}}"
        );
        $this->dropForeignKey(
            "{{%{$baseName}_updated_by_fk}}",
            "{{%$baseName}}"
        );
        $this->dropTable("{{%$baseName}}");
    }
}
