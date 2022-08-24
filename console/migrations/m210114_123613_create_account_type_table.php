<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%account_type}}`.
 */
class m210114_123613_create_account_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%account_type}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull()->unique()
        ]);
        $this->batchInsert('{{%account_type}}', ['type'], [
            ['Bank'],
            ['Income'],
            ['Other Income'],
            ['Expense'],
            ['Cost Of Sales'],
            ['Other Expense'],
            ['Current Asset'],
            ['Fixed Asset'],
            ['Accounts Receivable'],
            ['Current Liability'],
            ['Long Term Liability'],
            ['Accounts Payable'],
            ['Equity'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%account_type}}');
    }
}
