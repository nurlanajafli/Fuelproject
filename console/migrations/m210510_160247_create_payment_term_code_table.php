<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment_term_code}}`.
 */
class m210510_160247_create_payment_term_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment_term_code}}', [
            'id' => $this->primaryKey(),
            'description' => $this->string(),
            'disc_days' => $this->integer(),
            'disc_amt' => $this->decimal(10, 2),
            'due_days' => $this->integer(),
        ]);
        $this->batchInsert(
            '{{%payment_term_code}}',
            ['description', 'disc_days', 'disc_amt', 'due_days'],
            [
                ['Receipt', 0, 0, 0],
                ['Net 30', 0, 0, 30],
                ['Net 15', 0, 0, 15],
                ['2/10 Net 30', 10, 0.02, 30],
            ]
        );

        $this->addColumn('{{%payment_term_code}}', 'created_at', $this->timestamp());
        $this->addColumn('{{%payment_term_code}}', 'updated_at', $this->timestamp());
        $this->addColumn('{{%payment_term_code}}', 'created_by', $this->integer());
        $this->addColumn('{{%payment_term_code}}', 'updated_by', $this->integer());
        $this->addForeignKey(
            '{{%payment_term_code_created_by_fk}}',
            '{{%payment_term_code}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payment_term_code_updated_by_fk}}',
            '{{%payment_term_code}}',
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
        $this->dropForeignKey(
            '{{%payment_term_code_created_by_fk}}',
            '{{%payment_term_code}}'
        );
        $this->dropForeignKey(
            '{{%payment_term_code_updated_by_fk}}',
            '{{%payment_term_code}}'
        );
        $this->dropColumn('{{%payment_term_code}}', 'created_at');
        $this->dropColumn('{{%payment_term_code}}', 'updated_at');
        $this->dropColumn('{{%payment_term_code}}', 'created_by');
        $this->dropColumn('{{%payment_term_code}}', 'updated_by');

        $this->dropTable('{{%payment_term_code}}');
    }
}
