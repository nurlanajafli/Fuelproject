<?php

use yii\db\Migration;

/**
 * Class m210116_132528_driver_compliance_add_next_dot_physical
 */
class m210116_132528_driver_compliance_add_next_dot_physical extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%driver_compliance}}', 'next_dot_physical', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%driver_compliance}}', 'next_dot_physical');
    }
}
