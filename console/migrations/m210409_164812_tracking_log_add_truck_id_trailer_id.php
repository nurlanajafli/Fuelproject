<?php

use yii\db\Migration;

/**
 * Class m210409_164812_tracking_log_add_truck_id_trailer_id
 */
class m210409_164812_tracking_log_add_truck_id_trailer_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $baseTable = 'tracking_log';
        $this->addColumn("{{%$baseTable}}", 'truck_id', $this->integer());
        $this->addColumn("{{%$baseTable}}", 'trailer_id', $this->integer());
        $this->addForeignKey(
            "{{%{$baseTable}_truck_id_fk}}",
            "{{%$baseTable}}",
            'truck_id',
            "{{%truck}}",
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseTable}_trailer_id_fk}}",
            "{{%$baseTable}}",
            'trailer_id',
            "{{%trailer}}",
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
        $baseTable = 'tracking_log';
        $this->dropForeignKey(
            "{{%{$baseTable}_truck_id_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropForeignKey(
            "{{%{$baseTable}_trailer_id_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropColumn("{{%$baseTable}}", 'truck_id');
        $this->dropColumn("{{%$baseTable}}", 'trailer_id');
    }
}
