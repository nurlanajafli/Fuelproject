<?php

use yii\db\Migration;

/**
 * Class m210327_140353_driver_add_auth_columns
 */
class m210327_140353_driver_add_auth_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%driver}}', 'password_hash', $this->string());
        $this->createIndex('{{%driver_email_address_unique}}', '{{%driver}}', 'email_address', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%driver}}', 'password_hash');
        $this->dropIndex('{{%driver_email_address_unique}}', '{{%driver}}');
    }
}
