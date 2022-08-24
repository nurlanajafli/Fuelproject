<?php

use yii\db\Migration;

/**
 * Class m201126_164613_add_unique_index_to_company_business_direction
 */
class m201126_164613_add_unique_index_to_company_business_direction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex(
            '{{%company_business_direction_company_id_business_direction_id_unique}}',
            '{{%company_business_direction}}',
            ['company_id', 'business_direction_id'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%company_business_direction_company_id_business_direction_id_unique}}',
            '{{%company_business_direction}}'
        );
    }
}
