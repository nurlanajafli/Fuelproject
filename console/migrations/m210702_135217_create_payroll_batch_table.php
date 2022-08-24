<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payroll_batch}}`.
 */
class m210702_135217_create_payroll_batch_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payroll_batch}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(),
            'status' => $this->string(),
            'batch_date' => $this->date(),
            'check_date' => $this->date(),
            'period_start' => $this->date(),
            'period_end' => $this->date(),
            'posted' => $this->integer(),
            'unposted' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        
        $this->addForeignKey(
            '{{%payroll_batch_created_by_fk}}',
            '{{%payroll_batch}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%payroll_batch_updated_by_fk}}',
            '{{%payroll_batch}}',
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
            '{{%payroll_batch_created_by_fk}}',
            '{{%payroll_batch}}'
        );
        $this->dropForeignKey(
            '{{%payroll_batch_updated_by_fk}}',
            '{{%payroll_batch}}'
        );
        $this->dropTable('{{%payroll_batch}}');
    }
}
