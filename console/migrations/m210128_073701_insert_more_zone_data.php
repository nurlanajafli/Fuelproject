<?php

use yii\db\Migration;

/**
 * Class m210128_073701_insert_more_zone_data
 */
class m210128_073701_insert_more_zone_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('{{%zone}}', ['code', 'description'], [
            ['LO', 'Local'],
            ['NC', 'Northern Canada'],
            ['NE', 'Northeast US'],
            ['NW', 'Northwest US'],
            ['SE', 'Southeast US'],
            ['SW', 'Southwest US'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%zone}}', ['code' => ["LO", "NC", "NE", "NW", "SE", "SW"]]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210128_073701_insert_more_zone_data cannot be reverted.\n";

        return false;
    }
    */
}
