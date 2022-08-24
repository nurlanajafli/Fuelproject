<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%driver_compliance}}`.
 */
class m210108_100656_create_driver_compliance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%driver_compliance}}', [
            'id' => $this->primaryKey(),
            'driver_id' => $this->integer()->notNull()->unique(),
            // CDL (Commercial Driver's License)
            'cdl_number' => $this->string(),
            'cdl_state_id' => $this->integer(),
            'cdl_expires' => $this->date(),
            'haz_mat' => $this->boolean()->notNull(),
            'haz_mat_expires' => $this->date(),
            // Access
            'ace_id' => $this->string(),
            'fast_id' => $this->string(),
            'twic_exp' => $this->date(),
            // Activity Tracking
            'last_drug_test' => $this->date(),
            'last_alcohol_test' => $this->date(),
            'work_auth_expires' => $this->date(),
            'next_ffd_evaluation' => $this->date(),
            'next_h2s_certification' => $this->date(),
            'next_vio_review' => $this->date(),
            'next_mvr_review' => $this->date(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%driver_compliance_driver_id_fk}}',
            '{{%driver_compliance}}',
            'driver_id',
            '{{%driver}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%driver_compliance_cdl_state_id_fk}}',
            '{{%driver_compliance}}',
            'cdl_state_id',
            '{{%state}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%driver_compliance_updated_by_fk}}',
            '{{%driver_compliance}}',
            'updated_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%driver_compliance_created_by_fk}}',
            '{{%driver_compliance}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->alterColumn('{{%unit}}', 'updated_by', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%driver_compliance_driver_id_fk}}',
            '{{%driver_compliance}}'
        );
        $this->dropForeignKey(
            '{{%driver_compliance_cdl_state_id_fk}}',
            '{{%driver_compliance}}'
        );
        $this->dropForeignKey(
            '{{%driver_compliance_updated_by_fk}}',
            '{{%driver_compliance}}'
        );
        $this->dropForeignKey(
            '{{%driver_compliance_created_by_fk}}',
            '{{%driver_compliance}}'
        );
        $this->dropTable('{{%driver_compliance}}');
        $this->alterColumn('{{%unit}}', 'updated_by', $this->integer()->notNull());
    }
}
