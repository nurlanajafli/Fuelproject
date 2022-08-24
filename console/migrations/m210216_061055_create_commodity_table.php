<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%commodity}}`.
 */
class m210216_061055_create_commodity_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $baseTable = 'commodity';
        $this->createTable("{{%$baseTable}}", [
            'id' => $this->primaryKey(),
            'description' => $this->string(),
            'hazmat_code' => $this->string(),
            'class' => $this->string(),
            'type' => $this->string(),
        ]);
        $this->addForeignKey(
            "{{%{$baseTable}_hazmat_code_fk}}",
            "{{%$baseTable}}",
            'hazmat_code',
            "{{%hazmat}}",
            'code',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $baseTable = 'commodity';
        $this->dropForeignKey(
            "{{%{$baseTable}_hazmat_code_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropTable("{{%$baseTable}}");
    }
}
