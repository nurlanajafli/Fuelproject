<?php

use yii\db\Migration;

/**
 * Class m211025_112922_alter_table_load_rating_matrix
 */
class m211025_112922_alter_table_load_rating_matrix extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%load_rating_zipzip}}', 'created_at', $this->integer(11));
        $this->addColumn('{{%load_rating_zonezone}}', 'created_at', $this->integer(11));
        $this->addColumn('{{%load_rating_geograph}}', 'created_at', $this->integer(11));
        $this->addColumn('{{%load_rating_distance}}', 'created_at', $this->integer(11));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%load_rating_zipzip}}', 'created_at');
        $this->dropColumn('{{%load_rating_zonezone}}', 'created_at');
        $this->dropColumn('{{%load_rating_geograph}}', 'created_at');
        $this->dropColumn('{{%load_rating_distance}}', 'created_at');
    }
}
