<?php

use yii\db\Migration;

/**
 * Class m201207_081729_truck_type_extended
 */
class m201207_081729_truck_type_extended extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%truck_type}}', 'max_weight', $this->integer()->notNull()->defaultValue(0));
        $this->addColumn('{{%truck_type}}', 'axles', $this->integer()->notNull()->defaultValue(0));
        $this->addColumn('{{%truck_type}}', 'height', $this->integer()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%truck_type}}', 'max_weight');
        $this->dropColumn('{{%truck_type}}', 'axles');
        $this->dropColumn('{{%truck_type}}', 'height');
    }

}
