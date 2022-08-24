<?php

use yii\db\Migration;

/**
 * Class m210420_093502_trailer_optional_fields
 */
class m210420_093502_trailer_optional_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%trailer}}', 'trailer_no', $this->string()->notNull());
        $this->alterColumn('{{%trailer}}', 'year', $this->integer());
        $this->alterColumn('{{%trailer}}', 'make', $this->string());
        $this->alterColumn('{{%trailer}}', 'model', $this->string());
        $this->alterColumn('{{%trailer}}', 'tare', $this->integer());
        $this->alterColumn('{{%trailer}}', 'serial', $this->string());
        $this->alterColumn('{{%trailer}}', 'license', $this->string());
        $this->alterColumn('{{%trailer}}', 'license_state_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%trailer}}', 'license_state_id', $this->integer()->notNull());
        $this->alterColumn('{{%trailer}}', 'license', $this->string()->notNull());
        $this->alterColumn('{{%trailer}}', 'serial', $this->string()->notNull());
        $this->alterColumn('{{%trailer}}', 'tare', $this->integer()->notNull());
        $this->alterColumn('{{%trailer}}', 'model', $this->string()->notNull());
        $this->alterColumn('{{%trailer}}', 'make', $this->string()->notNull());
        $this->alterColumn('{{%trailer}}', 'year', $this->integer()->notNull());
        $this->alterColumn('{{%trailer}}', 'trailer_no', $this->string());
    }
}
