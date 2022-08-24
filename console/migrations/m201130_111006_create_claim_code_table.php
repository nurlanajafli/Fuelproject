<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%claim_code}}`.
 */
class m201130_111006_create_claim_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%claim_code}}', [
            'code' => $this->string()->notNull(),
            'description' => $this->string(),
        ]);
        $this->addPrimaryKey('{{%claim_code_code_pk}}', '{{%claim_code}}', 'code');
        $this->batchInsert('{{%claim_code}}', ['code', 'description'], [
            ['DAMAGE', 'Freight Damaged'],
            ['LOST', 'Freight Lost'],
            ['SHORT', 'Freight Shortage'],
            ['SHRINK', 'Shrinkage'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('{{%claim_code_code_pk}}', '{{%claim_code}}');
        $this->dropTable('{{%claim_code}}');
    }
}
