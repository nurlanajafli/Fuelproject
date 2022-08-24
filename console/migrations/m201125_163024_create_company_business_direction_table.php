<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%company_business_direction}}`.
 */
class m201125_163024_create_company_business_direction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%company_business_direction}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'business_direction_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey(
            '{{%company_business_direction_company_id_fk}}',
            '{{%company_business_direction}}',
            'company_id',
            '{{%company}}',
            'id',
            'RESTRICT',
            'RESTRICT',
        );
        $this->addForeignKey(
            '{{%company_business_direction_business_direction_id_fk}}',
            '{{%company_business_direction}}',
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
        $this->dropForeignKey('{{%company_business_direction_business_direction_id_fk}}', '{{%company_business_direction}}');
        $this->dropForeignKey('{{%company_business_direction_company_id_fk}}', '{{%company_business_direction}}');
        $this->dropTable('{{%company_business_direction}}');
    }
}
