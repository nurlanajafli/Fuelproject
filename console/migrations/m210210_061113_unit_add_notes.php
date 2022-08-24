<?php

use yii\db\Migration;

/**
 * Class m210210_061113_unit_add_notes
 */
class m210210_061113_unit_add_notes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%unit}}', 'notes', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%unit}}', 'notes');
    }
}
