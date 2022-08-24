<?php

use yii\db\Migration;

/**
 * Class m210403_124436_tracking_log
 */
class m210403_124436_tracking_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $baseTable = 'tracking_log';
        $this->createTable("{{%$baseTable}}", [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),
            'location_id' => $this->integer()->notNull(),
            'zone' => $this->string(),
            'created_at' => 'timestamp with time zone',
            'created_by' => $this->integer(),
            'updated_at' => 'timestamp with time zone',
            'updated_by' => $this->integer(),
            'unit_id' => $this->integer()
        ]);
        $this->addForeignKey(
            "{{%{$baseTable}_location_id_fk}}",
            "{{%$baseTable}}",
            'location_id',
            '{{%location}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseTable}_zone_fk}}",
            "{{%$baseTable}}",
            'zone',
            '{{%zone}}',
            'code',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseTable}_created_by_fk}}",
            "{{%$baseTable}}",
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseTable}_updated_by_fk}}",
            "{{%$baseTable}}",
            'updated_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            "{{%{$baseTable}_unit_id_fk}}",
            "{{%$baseTable}}",
            'unit_id',
            '{{%unit}}',
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
            "{{%{$baseTable}_location_id_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropForeignKey(
            "{{%{$baseTable}_zone_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropForeignKey(
            "{{%{$baseTable}_created_by_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropForeignKey(
            "{{%{$baseTable}_updated_by_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropForeignKey(
            "{{%{$baseTable}_unit_id_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropTable("{{%$baseTable}}");
    }
}
