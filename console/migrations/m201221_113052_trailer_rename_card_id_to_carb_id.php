<?php

use yii\db\Migration;

/**
 * Class m201221_113052_trailer_rename_card_id_to_carb_id
 */
class m201221_113052_trailer_rename_card_id_to_carb_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%trailer}}', 'card_id', 'carb_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%trailer}}', 'carb_id', 'card_id');
    }
}
