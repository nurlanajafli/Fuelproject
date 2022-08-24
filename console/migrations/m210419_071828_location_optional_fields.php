<?php

use yii\db\Migration;

/**
 * Class m210419_071828_location_optional_fields
 */
class m210419_071828_location_optional_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%location}}', 'location_name', $this->string());
        $this->alterColumn('{{%location}}', 'main_phone', $this->string());
        $this->alterColumn('{{%location}}', 'emergency', $this->string());
        $this->alterColumn('{{%location}}', 'contact', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%location}}', 'contact', $this->string()->notNull());
        $this->alterColumn('{{%location}}', 'emergency', $this->string()->notNull());
        $this->alterColumn('{{%location}}', 'main_phone', $this->string()->notNull());
        $this->alterColumn('{{%location}}', 'location_name', $this->string()->notNull());
    }

}
