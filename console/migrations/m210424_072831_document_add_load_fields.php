<?php

use yii\db\Migration;

/**
 * Class m210424_072831_document_add_load_fields
 */
class m210424_072831_document_add_load_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%document}}', 'created_by', $this->integer()->notNull());
        $this->alterColumn('{{%document}}', 'created_at', $this->timestamp()->notNull());
        $this->addColumn('{{%document}}', 'updated_by', $this->integer());
        $this->addColumn('{{%document}}', 'updated_at', $this->timestamp());
        $this->addForeignKey(
            '{{%document_updated_by_fk}}',
            '{{%document}}',
            'updated_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->createTable('{{%document_code}}', [
            'code' => $this->string()->notNull(),
            'description' => $this->string()
        ]);
        $this->addPrimaryKey('{{%document_code_code_pk}}', '{{%document_code}}', 'code');
        $this->addColumn('{{%document}}', 'code', $this->string());
        $this->addForeignKey(
            '{{%document_code_fk}}',
            '{{%document}}',
            'code',
            '{{%document_code}}',
            'code',
            'RESTRICT',
            'CASCADE'
        );
        $this->batchInsert('{{%document_code}}', ['code', 'description'], [
            ['BL', 'Bill of Lading'],
            ['DR', 'Delivery Receipt'],
            ['IC', 'Interchange'],
            ['LR', 'Load Receipt'],
            ['PO', 'Purchase Order'],
            ['PS', 'Packing Slip'],
            ['ST', 'Scale Ticket'],
            ['TA', 'Transport Agreement'],
            ['WO', 'Work Order'],
            ['OR', 'Other Receipt'],
            ['OB', 'Other Backup'],
        ]);
        $this->addColumn('{{%document}}', 'load_id', $this->integer());
        $this->addForeignKey(
            '{{%document_load_id_fk}}',
            '{{%document}}',
            'load_id',
            '{{%load}}',
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
        $this->alterColumn('{{%document}}', 'created_by', $this->integer());
        $this->alterColumn('{{%document}}', 'created_at', 'timestamp with time zone');
        $this->dropForeignKey(
            '{{%document_updated_by_fk}}',
            '{{%document}}'
        );
        $this->dropColumn('{{%document}}', 'updated_by');
        $this->dropColumn('{{%document}}', 'updated_at');
        $this->dropForeignKey(
            '{{%document_code_fk}}',
            '{{%document}}'
        );
        $this->dropColumn('{{%document}}', 'code');
        $this->dropPrimaryKey('{{%document_code_code_pk}}', '{{%document_code}}');
        $this->dropTable('{{%document_code}}');
        $this->dropForeignKey(
            '{{%document_load_id_fk}}',
            '{{%document}}'
        );
        $this->dropColumn('{{%document}}', 'load_id');
    }
}
