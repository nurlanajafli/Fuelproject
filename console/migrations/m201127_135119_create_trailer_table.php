<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%trailer}}`.
 */
class m201127_135119_create_trailer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%trailer}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'year' => $this->integer()->notNull(),
            'make' => $this->string()->notNull(),
            'model' => $this->string()->notNull(),
            'tare' => $this->integer()->notNull(),
            'length' => $this->integer(),
            'height' => $this->integer(),
            'in_svc' => $this->date(),
            'status' => $this->string()->notNull(),
            'serial' => $this->string()->notNull(),
            'license' => $this->string()->notNull(),
            'license_state_id' => $this->integer()->notNull(),
            'card_id' => $this->string(),
            'office_id' => $this->integer(),
            'notes' => $this->text(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
        ]);
        $this->addForeignKey(
            '{{%trailer_type_fk}}',
            '{{%trailer}}',
            'type',
            '{{%trailer_type}}',
            'type',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%trailer_license_state_id_fk}}',
            '{{%trailer}}',
            'license_state_id',
            '{{%state}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%trailer_office_id_fk}}',
            '{{%trailer}}',
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
            '{{%trailer_type_fk}}',
            '{{%trailer}}'
        );
        $this->dropForeignKey(
            '{{%trailer_license_state_id_fk}}',
            '{{%trailer}}'
        );
        $this->dropForeignKey(
            '{{%trailer_office_id_fk}}',
            '{{%trailer}}'
        );
        $this->dropTable('{{%trailer}}');
    }
}
