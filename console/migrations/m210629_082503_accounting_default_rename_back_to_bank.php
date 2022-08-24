<?php

use yii\db\Migration;

/**
 * Class m210629_082503_accounting_default_rename_back_to_bank
 */
class m210629_082503_accounting_default_rename_back_to_bank extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%accounting_default}}', 'default_check_writing_back_account_id', 'default_check_writing_bank_account_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%accounting_default}}', 'default_check_writing_bank_account_id', 'default_check_writing_back_account_id');
    }
}
