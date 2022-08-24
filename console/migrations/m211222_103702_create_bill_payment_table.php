<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bill_payment}}`.
 */
class m211222_103702_create_bill_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'bill_id' => $this->integer(),
            'date' => $this->timestamp()->notNull(),
            'account' => $this->string()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'office_id' => $this->integer(),
            'our_ref' => $this->string(),
            'udf' => $this->string(),
            'memo' => $this->string(),
            'created_at' => $this->timestamp(),
            'created_by' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey(
            '{{%payment_bill_id_fk}}',
            '{{%payment}}',
            'bill_id',
            '{{%bill}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payment_account_fk}}',
            '{{%payment}}',
            'account',
            '{{%account}}',
            'account',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payment_office_id_fk}}',
            '{{%payment}}',
            'office_id',
            '{{%office}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payment_created_by_fk}}',
            '{{%payment}}',
            'created_by',
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
        $this->dropForeignKey(
            '{{%payment_bill_id_fk}}',
            '{{%payment}}'
        );
        $this->dropForeignKey(
            '{{%payment_account_fk}}',
            '{{%payment}}',
        );
        $this->dropForeignKey(
            '{{%payment_office_id_fk}}',
            '{{%payment}}',
        );
        $this->dropForeignKey(
            '{{%payment_created_by_fk}}',
            '{{%payment}}',
        );
        $this->dropTable('{{%payment}}');
    }
}
