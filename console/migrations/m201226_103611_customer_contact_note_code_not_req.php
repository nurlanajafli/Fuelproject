<?php

use yii\db\Migration;

/**
 * Class m201226_103611_customer_contact_note_code_not_req
 */
class m201226_103611_customer_contact_note_code_not_req extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%customer_contact_note}}', 'code', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%customer_contact_note}}', 'code', $this->string()->notNull());
    }
}
