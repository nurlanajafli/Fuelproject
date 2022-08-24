<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%location}}`.
 */
class m201212_104240_create_location_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%location}}', [
            'id' => $this->primaryKey(),
            'company_name' => $this->string()->notNull(),
            'location_name' => $this->string()->notNull(),
            'address' => $this->string()->notNull(),
            'city' => $this->string()->notNull(),
            'state_id' => $this->integer(),
            'zip' => $this->string(10)->notNull(),
            'main_phone' => $this->string()->notNull(),
            'main_800' => $this->string(),
            'main_fax' => $this->string(),
            //Emergency
            //Business Hours
            //Zone
            'office_id' => $this->integer(),
            'contact' => $this->string()->notNull(),
            'website' => $this->string(),
            //Bill To
            'notes' => $this->text(),
            //Directions
            'appointment_required' => $this->boolean()->notNull(),
            'trailer_pool_location' => $this->boolean()->notNull(),
            'lat' => $this->double()->notNull()->check('lat > -90 AND lat <= 90'),
            'long' => $this->double()->notNull()->check('lat > -180 AND lat <= 180'),
        ]);
        $this->addForeignKey(
            '{{%location_state_id_fk}}',
            '{{%location}}',
            'state_id',
            '{{%state}}',
            'id',
            'RESTRICT',
            'RESTRICT',
        );
        $this->addForeignKey(
            '{{%location_office_id_fk}}',
            '{{%location}}',
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
            '{{%location_state_id_fk}}',
            '{{%location}}'
        );
        $this->dropForeignKey(
            '{{%location_office_id_fk}}',
            '{{%location}}'
        );
        $this->dropTable('{{%location}}');
    }
}
