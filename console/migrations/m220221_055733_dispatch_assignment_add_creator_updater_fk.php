<?php

use yii\db\Migration;

/**
 * Class m220221_055733_dispatch_assignment_add_creator_updater_fk
 */
class m220221_055733_dispatch_assignment_add_creator_updater_fk extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%dispatch_assignment}}', 'created_at', 'timestamp with time zone NOT NULL');
        $this->alterColumn('{{%dispatch_assignment}}', 'updated_at', 'timestamp with time zone');
        $this->alterColumn('{{%dispatch_assignment}}', 'created_by', $this->integer()->notNull());
        $this->addForeignKey('{{%dispatch_assignment_created_by_fk}}', '{{%dispatch_assignment}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%dispatch_assignment_updated_by_fk}}', '{{%dispatch_assignment}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%dispatch_assignment}}', 'created_at', 'timestamp without time zone NOT NULL');
        $this->alterColumn('{{%dispatch_assignment}}', 'updated_at', 'timestamp without time zone');
        $this->alterColumn('{{%dispatch_assignment}}', 'created_by', $this->integer());
        $this->dropForeignKey('{{%dispatch_assignment_created_by_fk}}', '{{%dispatch_assignment}}');
        $this->dropForeignKey('{{%dispatch_assignment_updated_by_fk}}', '{{%dispatch_assignment}}');
    }
}
