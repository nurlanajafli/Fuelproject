<?php

use yii\db\Migration;

/**
 * Class m211006_155835_department_drop_parent_id_office_id
 */
class m211006_155835_department_drop_parent_id_office_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('{{%department_parent_id_fk}}', '{{%department}}');
        $this->dropForeignKey('{{%department_office_id_fk}}', '{{%department}}');
        $this->dropColumn('{{%department}}', 'parent_id');
        $this->dropColumn('{{%department}}', 'office_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%department}}', 'parent_id', $this->integer());
        $this->addColumn('{{%department}}', 'office_id', $this->integer());
        $this->addForeignKey('{{%department_parent_id_fk}}', '{{%department}}', 'parent_id', '{{%department}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%department_office_id_fk}}', '{{%department}}', 'office_id', '{{%office}}', 'id', 'RESTRICT', 'CASCADE');
    }
}
