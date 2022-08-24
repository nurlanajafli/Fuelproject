<?php

use yii\db\Migration;

/**
 * Class m210106_101641_office_data
 */
class m210106_101641_office_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	$this->insert('{{%office}}', [
	    'id' => 1, 
	    'office' => 'Main Office', 
	    'city' => 'Orlando', 
	    'state_id' => 9 // FL
	]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%office}}', ['id' => 1]);
    }
}
