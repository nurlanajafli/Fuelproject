<?php

use yii\db\Migration;

/**
 * Class m210326_160225_user_add_driver_id
 */
class m210326_160225_user_add_driver_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'driver_id', $this->integer());
        $this->addForeignKey(
            '{{%user_driver_id_fk}}',
            '{{%user}}',
            'driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->dropColumn('{{%user}}', 'scope');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%user}}', 'scope', $this->string());
        $this->dropForeignKey(
            '{{%user_driver_id_fk}}',
            '{{%user}}'
        );
        $this->dropColumn('{{%user}}', 'driver_id');
    }
}
