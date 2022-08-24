<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%accident_code}}`.
 */
class m201130_113716_create_accident_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%accident_code}}', [
            'code' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),
        ]);
        $this->addPrimaryKey('{{%accident_code_code_pk}}', '{{%accident_code}}', 'code');
        $this->batchInsert('{{%accident_code}}', ['code', 'description'], [
            ['COLI', 'Collision With Injuries'],
            ['COLM', 'Collision - Multi Vehicle'],
            ['COLO', 'Collision - Stationary Object'],
            ['COLS', 'Collision - Single Vehicle'],
            ['TKO', 'Truck Damaged'],
            ['TLO', 'Trailer Damaged'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('{{%accident_code_code_pk}}', '{{%accident_code}}');
        $this->dropTable('{{%accident_code}}');
    }
}
