<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%fuelcard_account_config}}`.
 */
class m211122_113346_create_fuelcard_account_config_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%fuelcard_account_config}}', [
            'type' => $this->string(),
            'config' => $this->json()->notNull(),
        ]);
        $this->addPrimaryKey('pk_fuelcard_account_config_type', '{{%fuelcard_account_config}}','type');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%fuelcard_account_config}}');
    }
}
