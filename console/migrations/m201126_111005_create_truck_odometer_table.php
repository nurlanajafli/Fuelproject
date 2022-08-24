<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%truck_odometer}}`.
 */
class m201126_111005_create_truck_odometer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%truck_odometer}}', [
            'id' => $this->primaryKey(),
            'truck_id' => $this->integer()->notNull(),
            'odometer' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%truck_odometer_truck_id_fk}}',
            '{{%truck_odometer}}',
            'truck_id',
            '{{%truck}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%truck_odometer_updated_by_fk}}',
            '{{%truck_odometer}}',
            'updated_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%truck_odometer_created_by_fk}}',
            '{{%truck_odometer}}',
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
        $this->dropForeignKey('{{%truck_odometer_created_by_fk}}', '{{%truck_odometer}}');
        $this->dropForeignKey('{{%truck_odometer_updated_by_fk}}', '{{%truck_odometer}}');
        $this->dropForeignKey('{{%truck_odometer_truck_id_fk}}', '{{%truck_odometer}}');
        $this->dropTable('{{%truck_odometer}}');
    }
}
