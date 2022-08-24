<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%load_accessories}}`.
 */
class m210224_073124_create_load_accessories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%accessorial_pay}}', 'rate_each', $this->decimal(10, 2)->defaultValue(0));

        $this->createTable('{{%load_accessories}}', [
            'id' => $this->primaryKey(),
            'load_id' => $this->integer()->notNull(),
            'matrix_id' => $this->integer()->notNull(),
            'accessorial_id' => $this->integer()->notNull(),
            'reference' => $this->string(32),
            'rate_each' => $this->decimal(10,2)->defaultValue(0),
            'units' => $this->decimal(10)->defaultValue(1),
            'amount' => $this->decimal(10, 2)->defaultValue(0),
            'notes' => $this->text(),
        ]);

        $this->addForeignKey(
            'load_accessories_load_id_fk',
            '{{%load_accessories}}',
            'load_id',
            '{{%load}}',
            'id',
            'CASCADE',
            'CASCADE'
		);

        $this->addForeignKey(
            'load_accessories_accessorial_id_fk',
            '{{%load_accessories}}',
            'accessorial_id',
            '{{%accessorial_pay}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'load_accessories_matrix_id_fk',
            '{{%load_accessories}}',
            'matrix_id',
            '{{%accessorial_matrix}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%accessorial_pay}}', 'rate_each', $this->decimal(2)->defaultValue(0));
        $this->dropForeignKey('load_accessories_matrix_id_fk', '{{%load_accessories}}');
        $this->dropForeignKey('load_accessories_accessorial_id_fk', '{{%load_accessories}}');
        $this->dropForeignKey('load_accessories_load_id_fk', '{{%load_accessories}}');
        $this->dropTable('{{%load_accessories}}');
    }
}
