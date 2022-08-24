<?php

use yii\db\Migration;

/**
 * Class m211006_161405_user_add_profile_fields
 */
class m211006_161405_user_add_profile_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'default_office_id', $this->integer());
        $this->addColumn('{{%user}}', 'agent_and_subject_to_agent_rules', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn('{{%user}}', 'can_access_dispatch_data_from_all_offices', $this->boolean()->notNull()->defaultValue(false));
        $this->addForeignKey('{{%user__default_office}}', '{{%user}}', 'default_office_id', '{{%office}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%user__default_office}}', '{{%user}}');
        $this->dropColumn('{{%user}}', 'default_office_id');
        $this->dropColumn('{{%user}}', 'agent_and_subject_to_agent_rules');
        $this->dropColumn('{{%user}}', 'can_access_dispatch_data_from_all_offices');
    }
}
