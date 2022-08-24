<?php

use yii\db\Migration;
use common\enums\InvoiceItemCodeType;

/**
 * Handles the creation of table `{{%invoice_item_code}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%account}}`
 */
class m210610_144548_create_invoice_item_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoice_item_code}}', [
            'id' => $this->primaryKey(),
            'description' => $this->string(),
            'type' => $this->string(),
            'account_id' => $this->string(4)->notNull(),
        ]);
        
        // add foreign key for table `{{%account}}`
        $this->addForeignKey(
            '{{%invoice_item_code_account_id_fk}}',
            '{{%invoice_item_code}}',
            'account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->batchInsert(
            '{{%invoice_item_code}}',
            ['description', 'type', 'account_id'],
            [
                ['Transportation Revenue', InvoiceItemCodeType::INCOME, '3000'],
                ['Driver Charges', InvoiceItemCodeType::INCOME, '3001'],
                ['Accessorial Revenue', InvoiceItemCodeType::INCOME, '3010'],
                ['Truck Lease', InvoiceItemCodeType::INCOME, '3020'],
                ['13 Cents Per Mile', InvoiceItemCodeType::INCOME, '3025'],
                ['Trailer Fee', InvoiceItemCodeType::INCOME, '3030'],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%account}}`
        $this->dropForeignKey(
            '{{%invoice_item_code_account_id_fk}}',
            '{{%invoice_item_code}}'
        );

        $this->dropTable('{{%invoice_item_code}}');
    }
}
