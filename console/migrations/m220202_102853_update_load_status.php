<?php

use yii\db\Migration;

/**
 * Class m220202_102853_update_load_status
 */
class m220202_102853_update_load_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('load',['status'=>'Enrouted'],['status'=>'Enrolled']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->update('load',['status'=>'Enrolled'],['status'=>'Enrouted']);
    }

}
