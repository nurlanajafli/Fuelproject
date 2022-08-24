<?php

use yii\db\Migration;

/**
 * Class m211219_073804_report_add_signed_at
 */
class m211219_073804_report_add_signed_at extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%report}}', 'signed_at', $this->timestamp());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%report}}', 'signed_at');
    }
}
