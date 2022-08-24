<?php

use yii\db\Migration;

/**
 * Class m210223_133808_location_rename_long_to_lon
 */
class m210223_133808_location_rename_long_to_lon extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%location}}', 'long', 'lon');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%location}}', 'lon', 'long');
    }
}
