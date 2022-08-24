<?php

use yii\db\Migration;

/**
 * Class m210702_045619_load_alter_cpieces_cspace
 */
class m210702_045619_load_alter_cpieces_cspace extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%load}}', 'commodity_pieces', $this->decimal(10, 2));
        $this->alterColumn('{{%load}}', 'commodity_space', $this->decimal(10, 2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%load}}', 'commodity_pieces', $this->decimal());
        $this->alterColumn('{{%load}}', 'commodity_space', $this->decimal());
    }
}
