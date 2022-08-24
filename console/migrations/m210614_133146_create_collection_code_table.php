<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%collection_code}}`.
 */
class m210614_133146_create_collection_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%collection_code}}', [
            'code' => $this->string(4)->notNull(),
            'description' => $this->string(),
        ]);
        $this->addPrimaryKey('{{%collection_code_key_pk}}', '{{%collection_code}}', 'code');
        $this->batchInsert(
            '{{%collection_code}}',
            ['code', 'description'],
            [
                ['DC', 'Disputing Charges'],
                ['LM', 'Left Message'],
                ['NA', 'No Answer'],
                ['NP', 'Refuses to Pay'],
                ['PS', 'Payment Sent'],
                ['SI', 'Resent Invoice'],
                ['SS', 'Sent Statement'],
                ['WP30', 'Will Pay in 30 Days'],
                ['WP7', 'Will Pay in 7 Days'],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%collection_code}}');
    }
}
