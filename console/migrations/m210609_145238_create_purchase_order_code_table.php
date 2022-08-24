<?php

use yii\db\Migration;
use common\enums\PurchaseOrderType;

/**
 * Handles the creation of table `{{%purchase_order_code}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%account}}`
 */
class m210609_145238_create_purchase_order_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%purchase_order_code}}', [
            'code' => $this->string(4)->notNull(),
            'account_id' => $this->string(4)->notNull(),
            'description' => $this->string(),
            'type' => $this->string()->notNull(),
        ]);
        $this->addPrimaryKey('{{%purchase_order_code_key_pk}}', '{{%purchase_order_code}}', 'code');
        // add foreign key for table `{{%account}}`
        $this->addForeignKey(
            '{{%purchase_order_code_account_id_fk}}',
            '{{%purchase_order_code}}',
            'account_id',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        
        $this->batchInsert(
            '{{%purchase_order_code}}',
            ['code', 'account_id', 'description', 'type'],
            [
                ['100', '9999', 'Purchased Labor', PurchaseOrderType::COST_OF_SALES],
                ['200', '9999', 'Misc Expense',  PurchaseOrderType::CURRENT_ASSET],
                ['300', '1050', 'Advance',  PurchaseOrderType::FIXED_ASSET],
                ['400', '1050', 'Deductible',  PurchaseOrderType::EXPENSE],
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
            '{{%purchase_order_code_account_id_fk}}',
            '{{%purchase_order_code}}'
        );

        $this->dropTable('{{%purchase_order_code}}');
    }
}
