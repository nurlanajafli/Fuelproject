<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%report}}`.
 */
class m211117_124717_create_report_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%report}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'truck_id' => $this->integer(),
            'trailer_id' => $this->integer(),
            'driver_id' => $this->integer()->notNull(),
            'mileage' => $this->integer()->notNull(),
            'def_level' => $this->string()->notNull(),
            'fuel_level' => $this->string()->notNull(),
            'status' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->timestamp(),
            'updated_by' => $this->integer(),
        ]);
        $this->addForeignKey('{{%report__truck_fk}}', '{{%report}}', 'truck_id', '{{%truck}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%report__trailer_fk}}', '{{%report}}', 'trailer_id', '{{%trailer}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%report__driver_fk}}', '{{%report}}', 'driver_id', '{{%driver}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%report__truck_fk}}', '{{%report}}');
        $this->dropForeignKey('{{%report__trailer_fk}}', '{{%report}}');
        $this->dropForeignKey('{{%report__driver_fk}}', '{{%report}}');
        $this->dropTable('{{%report}}');
    }
}
