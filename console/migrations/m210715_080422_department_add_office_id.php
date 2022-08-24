<?php

use yii\db\Migration;

/**
 * Class m210715_080422_department_add_office_id
 */
class m210715_080422_department_add_office_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%department}}', 'office_id', $this->integer());
        $this->addForeignKey(
            '{{%department_office_id_fk}}',
            '{{%department}}',
            'office_id',
            '{{%office}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%department_office_id_fk}}',
            '{{%department}}'
        );
        $this->dropColumn('{{%department}}', 'office_id');
    }
}
