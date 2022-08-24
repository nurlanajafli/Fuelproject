<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%setting}}`.
 */
class m210330_054453_create_setting_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%setting}}', [
            'key' => $this->string()->notNull(),
            'value' => $this->string(),
            'created_at' => 'timestamp with time zone',
            'created_by' => $this->integer(),
            'updated_at' => 'timestamp with time zone',
            'updated_by' => $this->integer()
        ]);
        $this->addPrimaryKey('{{%setting_key_pk}}', '{{%setting}}', 'key');
        $this->addForeignKey(
            '{{%setting_created_by_fk}}',
            '{{%setting}}',
            'created_by',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%setting_updated_by_fk}}',
            '{{%setting}}',
            'updated_by',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%setting_created_by_fk}}',
            '{{%setting}}'
        );
        $this->dropForeignKey(
            '{{%setting_updated_by_fk}}',
            '{{%setting}}'
        );
        $this->dropPrimaryKey('{{%setting_key_pk}}', '{{%setting}}');
        $this->dropTable('{{%setting}}');
    }
}
