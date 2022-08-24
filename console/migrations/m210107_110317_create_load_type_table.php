<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%load_type}}`.
 */
class m210107_110317_create_load_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%load_type}}', [
            'id' => $this->primaryKey(),
            'description' => $this->string()->notNull()->unique()
        ]);
        $this->batchInsert('{{%load_type}}', ['description'], [
            ['ISSUE LOADS'],
            ['SOLO RUN'],
            ['SORT LOAD'],
            ['TEAM RUN'],
            ['TRUE TEAM ONLY']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%load_type}}');
    }
}
