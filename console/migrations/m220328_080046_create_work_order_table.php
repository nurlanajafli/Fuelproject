<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%work_order}}`.
 */
class m220328_080046_create_work_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%work_order}}', [
            'id' => $this->primaryKey(),
            'order_date' => $this->date()->notNull(),
            'order_type' => $this->string()->notNull(),
            'truck_id' => $this->integer(),
            'trailer_id' => $this->integer(),
            'vendor_id' => $this->integer(),
            'odometer' => $this->integer(),
        ]);
        $this->addForeignKey(
            '{{%work_order_truck_fk}}',
            '{{%work_order}}',
            'truck_id',
            '{{%truck}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%work_order_trailer_fk}}',
            '{{%work_order}}',
            'trailer_id',
            '{{%trailer}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%work_order_vendor_fk}}',
            '{{%work_order}}',
            'vendor_id',
            '{{%vendor}}',
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
            '{{%work_order_truck_fk}}',
            '{{%work_order}}'
        );
        $this->dropForeignKey(
            '{{%work_order_trailer_fk}}',
            '{{%work_order}}'
        );
        $this->dropForeignKey(
            '{{%work_order_vendor_fk}}',
            '{{%work_order}}'
        );
        $this->dropTable('{{%work_order}}');
    }
}
