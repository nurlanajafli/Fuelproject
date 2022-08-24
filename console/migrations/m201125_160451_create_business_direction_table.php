<?php

use yii\db\Migration;
use \common\enums\BusinessDirection;

/**
 * Handles the creation of table `{{%business_direction}}`.
 */
class m201125_160451_create_business_direction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%business_direction}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);
        $this->batchInsert('{{%business_direction}}', ['id', 'name'], [
            [BusinessDirection::TRUCKLOAD_DISPATCH, 'Truckload Dispatch'],
            [BusinessDirection::TRUCKLOAD_BROKERAGE, 'Truckload Brokerage'],
            [BusinessDirection::LTL_DISPATCH, 'LTL Dispatch'],
            [BusinessDirection::LTL_BROKERAGE, 'LTL Brokerage'],
            [BusinessDirection::INTERMODAL_DISPATCH, 'Intermodal Dispatch'],
            [BusinessDirection::INTERMODAL_BROKERAGE, 'Intermodal Brokerage'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%business_direction}}');
    }
}
