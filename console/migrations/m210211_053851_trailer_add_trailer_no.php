<?php

use yii\db\Migration;

/**
 * Class m210211_053851_trailer_add_trailer_no
 */
class m210211_053851_trailer_add_trailer_no extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%trailer}}', 'trailer_no', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%trailer}}', 'trailer_no');
    }
}
