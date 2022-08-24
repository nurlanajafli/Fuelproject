<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%report_flag}}`.
 */
class m211214_115746_create_report_flag_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%report_flag}}', [
            'id' => $this->primaryKey(),
            'report_id' => $this->integer()->notNull(),
            'flag' => $this->string()->notNull(),
        ]);
        $this->addForeignKey('{{%report_flag_report_id_fk}}', '{{%report_flag}}', 'report_id', '{{%report}}', 'id', 'RESTRICT', 'CASCADE');
        $this->createIndex('{{%report_flag_report_id_flag_idx}}', '{{%report_flag}}', ['report_id', 'flag'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%report_flag_report_id_fk}}', '{{%report_flag}}');
        $this->dropIndex('{{%report_flag_report_id_flag_idx}}', '{{%report}}');
        $this->dropTable('{{%report_flag}}');
    }
}
