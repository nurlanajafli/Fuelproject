<?php

use yii\db\Migration;

/**
 * Class m220217_064459_add_company_name_field_to_loadstops
 */
class m220217_064459_add_company_name_field_to_loadstops extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('load_stop', 'company_name', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('load_stop', 'company_name');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220217_064459_add_company_name_field_to_loadstops cannot be reverted.\n";

        return false;
    }
    */
}
