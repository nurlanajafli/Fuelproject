<?php

use yii\db\Migration;

/**
 * Class m211018_154914_load_add_matrix_number
 */
class m211018_154914_load_add_matrix_number extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%load}}', 'rating_matrix_id', $this->string(5));
        $this->addForeignKey('{{%load__rating_matrix_id_fk}}', '{{%load}}', 'rating_matrix_id', '{{%load_rating_matrix}}', 'number', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%load__rating_matrix_id_fk}}', '{{%load}}');
        $this->dropColumn('{{%load}}', 'rating_matrix_id');
    }
}
