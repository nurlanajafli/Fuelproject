<?php

use yii\db\Migration;

/**
 * Class m210908_054012_setting_drop_created_at_created_by
 */
class m210908_054012_setting_drop_created_at_created_by extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('{{%setting_created_by_fk}}', '{{%setting}}');
        $this->dropColumn('{{%setting}}', 'created_at');
        $this->dropColumn('{{%setting}}', 'created_by');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%setting}}', 'created_at', 'timestamp with time zone');
        $this->addColumn('{{%setting}}', 'created_by', $this->integer());
        $this->addForeignKey(
            '{{%setting_created_by_fk}}',
            '{{%setting}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }
}
