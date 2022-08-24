<?php

use yii\db\Migration;

/**
 * Class m210107_074808_add_marked_as_down_column
 */
class m210107_074808_add_marked_as_down_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%trailer}}', 'out_of_service', $this->date());
        $tables = ['{{%truck}}', '{{%trailer}}', '{{%driver}}', '{{%location}}', '{{%customer}}', '{{%vendor}}', '{{%carrier}}'];
        foreach ($tables as $table) {
            $this->addColumn($table, 'marked_as_down', $this->date());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%trailer}}', 'out_of_service');
        $tables = ['{{%truck}}', '{{%trailer}}', '{{%driver}}', '{{%location}}', '{{%customer}}', '{{%vendor}}', '{{%carrier}}'];
        foreach ($tables as $table) {
            $this->dropColumn($table, 'marked_as_down');
        }
    }
}
