<?php

use yii\db\Migration;

/**
 * Class m201229_062303_create_contact_tables
 */
class m201229_062303_create_contact_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%department}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]);
        $this->createIndex('{{%department_name_unique}}', '{{%department}}', 'name', true);
        $this->batchInsert('{{%department}}', ['name'], [
            ['Dispatch'],
            ['Shipping'],
            ['Payables'],
            ['Receivables'],
            ['Management'],
            ['Invoicing']
        ]);

        foreach (['location_contact', 'vendor_contact', 'customer_contact', 'carrier_contact'] as $table) {
            $mainTable = str_replace('_contact', '', $table);
            $columns = [
                'id' => $this->primaryKey(),
                "{$mainTable}_id" => $this->integer()->notNull(),
                "contact_name" => $this->string(),
                "department_id" => $this->integer(),
                "direct_line" => $this->string(),
                "direct_fax" => $this->string(),
                "extension" => $this->string(),
                "alt_phone_1" => $this->string(),
                "desc_1" => $this->string(),
                "alt_phone_2" => $this->string(),
                "desc_2" => $this->string(),
                "email" => $this->string(),
                "notes" => $this->text(),
            ];
            if ($mainTable == 'customer') {
                $columns['all_updates'] = $this->boolean()->notNull();
                $columns['booked'] = $this->boolean()->notNull();
                $columns['at_origin'] = $this->boolean()->notNull();
                $columns['delivered'] = $this->boolean()->notNull();
            }
            $this->createTable("{{%$table}}", $columns);
            $this->addForeignKey(
                "{{%{$table}_{$mainTable}_id_fk}}",
                "{{%{$table}}}",
                "{$mainTable}_id",
                "{{%{$mainTable}}}",
                'id',
                'RESTRICT',
                'CASCADE'
            );
            $this->addForeignKey(
                "{{%{$table}_department_id_fk}}",
                "{{%{$table}}}",
                "department_id",
                "{{%department}}",
                'id',
                'RESTRICT',
                'CASCADE'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        foreach (['location_contact', 'vendor_contact', 'customer_contact', 'carrier_contact'] as $table) {
            $mainTable = str_replace('_contact', '', $table);
            $this->dropForeignKey(
                "{{%{$table}_{$mainTable}_id_fk}}",
                "{{%{$table}}}"
            );
            $this->dropForeignKey(
                "{{%{$table}_department_id_fk}}",
                "{{%{$table}}}"
            );
            $this->dropTable("{{%$table}}");
        }

        $this->dropIndex('{{%department_name_unique}}', '{{%department}}');
        $this->dropTable('{{%department}}');
    }

}
