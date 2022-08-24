<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%trailer_type}}`.
 */
class m201124_123415_create_trailer_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%trailer_type}}', [
            'type' => $this->string()->notNull(),
            'height' => $this->integer()->notNull(),
            'length' => $this->integer()->notNull(),
            'description' => $this->string(),
        ]);
        $this->addPrimaryKey('{{%trailer_type_type_pk}}', '{{%trailer_type}}', 'type');
        $this->batchInsert('{{%trailer_type}}', ['type', 'height', 'length', 'description'], [
            ['NT', 110, 53, 'No Trailer'],
            ['PO', 110, 53, 'POWER ONLY'],
            ['R1', 110, 48, 'Refrigerated Van 48'],
            ['R2', 110, 53, 'Refrigerated Van 53'],
            ['V', 110, 53, '53 FT VAN'],
            ['V1', 110, 48, '1 PUP'],
            ['V2', 110, 53, '2 PUPS'],
            ['V3', 110, 53, 'Vented Van'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('{{%trailer_type_type_pk}}', '{{%trailer_type}}');
        $this->dropTable('{{%trailer_type}}');
    }
}
