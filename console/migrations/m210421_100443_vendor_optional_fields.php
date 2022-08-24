<?php

use yii\db\Migration;

/**
 * Class m210421_100443_vendor_optional_fields
 */
class m210421_100443_vendor_optional_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%vendor}}', 'address_1', $this->string());
        $this->alterColumn('{{%vendor}}', 'city', $this->string());
        $this->alterColumn('{{%vendor}}', 'zip', $this->string(10));
        $this->alterColumn('{{%vendor}}', 'main_phone', $this->string());
        $this->alterColumn('{{%vendor}}', 'ar_contact', $this->string());
        $this->alterColumn('{{%vendor}}', 'account_no', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%vendor}}', 'account_no', $this->string()->notNull());
        $this->alterColumn('{{%vendor}}', 'ar_contact', $this->string()->notNull());
        $this->alterColumn('{{%vendor}}', 'main_phone', $this->string()->notNull());
        $this->alterColumn('{{%vendor}}', 'zip', $this->string(10)->notNull());
        $this->alterColumn('{{%vendor}}', 'city', $this->string()->notNull());
        $this->alterColumn('{{%vendor}}', 'address_1', $this->string()->notNull());
    }
}
