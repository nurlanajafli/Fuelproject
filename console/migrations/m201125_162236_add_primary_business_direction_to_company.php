<?php

use yii\db\Migration;

/**
 * Class m201125_162236_add_primary_business_direction_to_company
 */
class m201125_162236_add_primary_business_direction_to_company extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%company}}', 'business_direction_id', $this->integer()->notNull());
        $this->addForeignKey(
            '{{%company_business_direction_id_fk}}',
            '{{%company}}',
            'business_direction_id',
            '{{%business_direction}}',
            'id',
            'RESTRICT',
            'RESTRICT',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%company_business_direction_id_fk}}', '{{%company}}');
        $this->dropColumn('{{%company}}', 'business_direction_id');
    }
}
