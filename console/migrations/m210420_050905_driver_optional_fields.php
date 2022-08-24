<?php

use yii\db\Migration;

/**
 * Class m210420_050905_driver_optional_fields
 */
class m210420_050905_driver_optional_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%driver}}', 'address_1', $this->string());
        $this->alterColumn('{{%driver}}', 'city', $this->string());
        $this->alterColumn('{{%driver}}', 'zip', $this->string());
        $this->alterColumn('{{%driver}}', 'state_id', $this->integer()->notNull());
        $this->alterColumn('{{%driver}}', 'telephone', $this->string());
        $this->alterColumn('{{%driver}}', 'cell_phone', $this->string());
        $this->alterColumn('{{%driver}}', 'social_sec_no', $this->string());
        $this->alterColumn('{{%driver}}', 'passport_no', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%driver}}', 'passport_no', $this->string()->notNull());
        $this->alterColumn('{{%driver}}', 'social_sec_no', $this->string()->notNull());
        $this->alterColumn('{{%driver}}', 'cell_phone', $this->string()->notNull());
        $this->alterColumn('{{%driver}}', 'telephone', $this->string()->notNull());
        $this->alterColumn('{{%driver}}', 'state_id', $this->integer());
        $this->alterColumn('{{%driver}}', 'zip', $this->string()->notNull());
        $this->alterColumn('{{%driver}}', 'city', $this->string()->notNull());
        $this->alterColumn('{{%driver}}', 'address_1', $this->string()->notNull());
    }
}
