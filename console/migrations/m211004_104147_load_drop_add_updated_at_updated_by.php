<?php

use yii\db\Migration;

/**
 * Class m211004_104147_load_drop_add_updated_at_updated_by
 */
class m211004_104147_load_drop_add_updated_at_updated_by extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%load_drop}}', 'updated_at', $this->timestamp());
        $this->addColumn('{{%load_drop}}', 'updated_by', $this->integer());
        $this->addForeignKey(
            '{{%load_drop__updater}}',
            '{{%load_drop}}',
            'updated_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%load_drop__updater}}',
            '{{%load_drop}}'
        );
        $this->dropColumn('{{%load_drop}}', 'updated_at');
        $this->dropColumn('{{%load_drop}}', 'updated_by');
    }
}
