<?php

use yii\db\Migration;

/**
 * Class m210126_141928_truck_down_columns
 */
class m210126_141928_truck_down_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $baseTable = 'truck';
        $table = "{{%$baseTable}}";
        $this->dropColumn($table, 'marked_as_down');

        $this->addColumn($table, 'is_down', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn($table, 'returns_to_service', $this->date());
        $this->addColumn($table, 'return_location_id', $this->integer());
        $this->addForeignKey(
            "{{%{$baseTable}_return_location_id_fk}}",
            "{{%$baseTable}}",
            'return_location_id',
            '{{%location}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addColumn($table, 'notify_all_dispatch_personnel', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn($table, 'downed_at', $this->timestamp());
        $this->addColumn($table, 'downed_by', $this->integer());
        $this->addForeignKey(
            "{{%{$baseTable}_downed_by_fk}}",
            "{{%$baseTable}}",
            'downed_by',
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
        $baseTable = 'truck';
        $table = "{{%$baseTable}}";
        $this->addColumn($table, 'marked_as_down', $this->date());

        $this->dropColumn($table, 'is_down');
        $this->dropColumn($table, 'returns_to_service');
        $this->dropForeignKey(
            "{{%{$baseTable}_return_location_id_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropColumn($table, 'return_location_id');
        $this->dropColumn($table, 'notify_all_dispatch_personnel');
        $this->dropForeignKey(
            "{{%{$baseTable}_downed_by_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropColumn($table, 'downed_at');
        $this->dropColumn($table, 'downed_by');
    }
}
