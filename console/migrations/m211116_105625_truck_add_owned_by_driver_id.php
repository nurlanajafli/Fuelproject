<?php

use yii\db\Migration;

/**
 * Class m211116_105625_truck_add_owned_by_driver_id
 */
class m211116_105625_truck_add_owned_by_driver_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%truck}}', 'owned_by_driver_id', $this->integer());
        $this->addForeignKey('{{%truck__owned_by_driver}}', '{{%truck}}', 'owned_by_driver_id', '{{%driver}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%truck__owned_by_driver}}', '{{%truck}}');
        $this->dropColumn('{{%truck}}', 'owned_by_driver_id');
    }
}
