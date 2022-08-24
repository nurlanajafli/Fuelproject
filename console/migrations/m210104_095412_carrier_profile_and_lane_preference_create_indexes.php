<?php

use yii\db\Migration;

/**
 * Class m210104_095412_carrier_profile_and_lane_preference_create_indexes
 */
class m210104_095412_carrier_profile_and_lane_preference_create_indexes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('{{%carrier_profile_carrier_id_unique}}', '{{%carrier_profile}}', 'carrier_id', true);
        $this->createIndex('{{%lane_preference_carrier_id_unique}}', '{{%lane_preference}}', 'carrier_id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%carrier_profile_carrier_id_unique}}', '{{%carrier_profile}}');
        $this->dropIndex('{{%lane_preference_carrier_id_unique}}', '{{%lane_preference}}');
    }

}
