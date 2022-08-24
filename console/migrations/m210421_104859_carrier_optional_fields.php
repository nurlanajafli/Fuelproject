<?php

use yii\db\Migration;

/**
 * Class m210421_104859_carrier_optional_fields
 */
class m210421_104859_carrier_optional_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%carrier}}', 'address_1', $this->string());
        $this->alterColumn('{{%carrier}}', 'city', $this->string());
        $this->alterColumn('{{%carrier}}', 'zip', $this->string(10));
        $this->alterColumn('{{%carrier}}', 'main_phone', $this->string());
        $this->alterColumn('{{%carrier}}', 'disp_contact', $this->string());
        $this->alterColumn('{{%carrier}}', 'ar_contact', $this->string());
        $this->alterColumn('{{%carrier}}', 'account_no', $this->string());
        $this->alterColumn('{{%carrier}}', 'federal_id', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%carrier}}', 'federal_id', $this->string());
        $this->alterColumn('{{%carrier}}', 'account_no', $this->string());
        $this->alterColumn('{{%carrier}}', 'ar_contact', $this->string());
        $this->alterColumn('{{%carrier}}', 'disp_contact', $this->string());
        $this->alterColumn('{{%carrier}}', 'main_phone', $this->string());
        $this->alterColumn('{{%carrier}}', 'zip', $this->string(10));
        $this->alterColumn('{{%carrier}}', 'city', $this->string());
        $this->alterColumn('{{%carrier}}', 'address_1', $this->string());
    }
}
