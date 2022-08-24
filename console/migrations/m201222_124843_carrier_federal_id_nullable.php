<?php

use yii\db\Migration;

/**
 * Class m201222_124843_carrier_federal_id_nullable
 */
class m201222_124843_carrier_federal_id_nullable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%carrier}}', 'federal_id', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%carrier}}', 'federal_id', $this->string()->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201222_124843_carrier_federal_id_nullable cannot be reverted.\n";

        return false;
    }
    */
}
