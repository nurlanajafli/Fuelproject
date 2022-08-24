<?php

use yii\db\Migration;

/**
 * Class m210421_085806_customer_optional_fields
 */
class m210421_085806_customer_optional_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%customer}}', 'address_1', $this->string());
        $this->alterColumn('{{%customer}}', 'city', $this->string());
        $this->alterColumn('{{%customer}}', 'zip', $this->string(10));
        $this->alterColumn('{{%customer}}', 'main_phone', $this->string());
        $this->alterColumn('{{%customer}}', 'disp_contact', $this->string());
        $this->alterColumn('{{%customer}}', 'ap_contact', $this->string());
        $this->alterColumn('{{%customer}}', 'account_no', $this->string());
        $this->alterColumn('{{%customer}}', 'federal_id', $this->string());
        $this->alterColumn('{{%customer}}', 'mc_id', $this->string());
        $this->alterColumn('{{%customer}}', 'scac', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%customer}}', 'scac', $this->string()->notNull());
        $this->alterColumn('{{%customer}}', 'mc_id', $this->string()->notNull());
        $this->alterColumn('{{%customer}}', 'federal_id', $this->string()->notNull());
        $this->alterColumn('{{%customer}}', 'account_no', $this->string()->notNull());
        $this->alterColumn('{{%customer}}', 'ap_contact', $this->string()->notNull());
        $this->alterColumn('{{%customer}}', 'disp_contact', $this->string()->notNull());
        $this->alterColumn('{{%customer}}', 'main_phone', $this->string()->notNull());
        $this->alterColumn('{{%customer}}', 'zip', $this->string(10)->notNull());
        $this->alterColumn('{{%customer}}', 'city', $this->string()->notNull());
        $this->alterColumn('{{%customer}}', 'address_1', $this->string()->notNull());
    }
}
