<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%work_order_item}}`.
 */
class m220407_121123_create_work_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%work_order_item}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'created_at' => 'timestamp with time zone NOT NULL',
            'updated_at' => 'timestamp with time zone NOT NULL',
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey(
            '{{%work_order_item_order_fk}}',
            '{{%work_order_item}}',
            'order_id',
            '{{%work_order}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%work_order_item_created_by_fk}}',
            '{{%work_order_item}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%work_order_item_updated_by_fk}}',
            '{{%work_order_item}}',
            'updated_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addColumn('{{%work_order}}', 'status', $this->string()->notNull());
        $this->addColumn('{{%work_order}}', 'authorized_by', $this->string());
        $this->addColumn('{{%work_order}}', 'created_at', 'timestamp with time zone NOT NULL');
        $this->addColumn('{{%work_order}}', 'updated_at', 'timestamp with time zone NOT NULL');
        $this->addColumn('{{%work_order}}', 'created_by', $this->integer()->notNull());
        $this->addColumn('{{%work_order}}', 'updated_by', $this->integer()->notNull());
        $this->addForeignKey(
            '{{%work_order_created_by_fk}}',
            '{{%work_order}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%work_order_updated_by_fk}}',
            '{{%work_order}}',
            'updated_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addColumn('{{%work_order}}', 'description', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%work_order_item_order_fk}}',
            '{{%work_order_item}}',
        );
        $this->dropForeignKey(
            '{{%work_order_item_created_by_fk}}',
            '{{%work_order_item}}'
        );
        $this->dropForeignKey(
            '{{%work_order_item_updated_by_fk}}',
            '{{%work_order_item}}'
        );
        $this->dropTable('{{%work_order_item}}');
        $this->dropColumn('{{%work_order}}', 'status');
        $this->dropColumn('{{%work_order}}', 'authorized_by');

        $this->dropForeignKey(
            '{{%work_order_created_by_fk}}',
            '{{%work_order}}'
        );
        $this->dropForeignKey(
            '{{%work_order_updated_by_fk}}',
            '{{%work_order}}'
        );
        $this->dropColumn('{{%work_order}}', 'created_at');
        $this->dropColumn('{{%work_order}}', 'updated_at');
        $this->dropColumn('{{%work_order}}', 'created_by');
        $this->dropColumn('{{%work_order}}', 'updated_by');
        $this->dropColumn('{{%work_order}}', 'description');
    }
}
