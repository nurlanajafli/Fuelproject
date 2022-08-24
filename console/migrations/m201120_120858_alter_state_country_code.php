<?php

use yii\db\Migration;

/**
 * Class m201120_120858_alter_state_country_code
 */
class m201120_120858_alter_state_country_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // ISO 3166-1 alpha-2
        $this->alterColumn('{{%state}}', 'country_code', $this->string(2)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%state}}', 'country_code', $this->string()->notNull());
    }

}
