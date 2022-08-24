<?php

use yii\db\Migration;

/**
 * Class m210725_044728_load_drop_cleared
 */
class m210725_044728_load_drop_cleared extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%load}}', 'cleared');
        $this->addColumn('{{%load}}', 'scans', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%load}}', 'cleared', $this->boolean());
        $this->dropColumn('{{%load}}', 'scans');
    }
}
