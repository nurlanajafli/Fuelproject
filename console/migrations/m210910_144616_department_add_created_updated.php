<?php

use yii\db\Migration;

/**
 * Class m210910_144616_department_add_created_updated
 */
class m210910_144616_department_add_created_updated extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%department}}', 'created_at', $this->timestamp());
        $this->addColumn('{{%department}}', 'created_by', $this->integer());
        $this->addColumn('{{%department}}', 'updated_at', $this->timestamp());
        $this->addColumn('{{%department}}', 'updated_by', $this->integer());
        $this->addForeignKey(
            '{{%department__creator}}',
            '{{%department}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%department__updater}}',
            '{{%department}}',
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
            '{{%department__creator}}',
            '{{%department}}'
        );
        $this->dropForeignKey(
            '{{%department__updater}}',
            '{{%department}}'
        );
        $this->dropColumn('{{%department}}', 'created_at');
        $this->dropColumn('{{%department}}', 'created_by');
        $this->dropColumn('{{%department}}', 'updated_at');
        $this->dropColumn('{{%department}}', 'updated_by');
    }
}
