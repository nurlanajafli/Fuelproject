<?php

use yii\db\Migration;

/**
 * Class m210520_102713_company_insert_row
 */
class m210520_102713_company_insert_row extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%company}}', 'logo', $this->string());
        $this->addColumn('{{%company}}', 'created_at', $this->timestamp()->notNull());
        $this->addColumn('{{%company}}', 'updated_at', $this->timestamp());
        $this->addColumn('{{%company}}', 'created_by', $this->integer());
        $this->addColumn('{{%company}}', 'updated_by', $this->integer());
        $this->dropForeignKey(
            '{{%company_state_id_fk}}',
            '{{%company}}'
        );
        $this->addForeignKey(
            '{{%company_state_id_fk}}',
            '{{%company}}',
            'state_id',
            '{{%state}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%company_created_by_fk}}',
            '{{%company}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%company_updated_by_fk}}',
            '{{%company}}',
            'updated_by',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->insert('{{%company}}', [
            'id' => 1,
            'name' => 'Gurman Trucking Inc',
            'address_1' => '',
            'city' => '',
            'zip' => '',
            'country' => '',
            'main_phone' => '',
            'accounting_phone' => '',
            'ar_contact' => '',
            'ap_contact' => '',
            'business_type' => '',
            'federal_id' => '',
            'dot_id' => '',
            'mc_id' => '',
            'scac' => '',
            'business_direction_id' => \common\enums\BusinessDirection::TRUCKLOAD_DISPATCH,
            'created_at' => new \yii\db\Expression('LOCALTIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%company}}', ['id' => 1]);
        $this->alterColumn('{{%company}}', 'logo', $this->string()->notNull());
        $this->dropForeignKey(
            '{{%company_state_id_fk}}',
            '{{%company}}'
        );
        $this->addForeignKey(
            '{{%company_state_id_fk}}',
            '{{%company}}',
            'state_id',
            '{{%state}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
        $this->dropForeignKey(
            '{{%company_created_by_fk}}',
            '{{%company}}'
        );
        $this->dropForeignKey(
            '{{%company_updated_by_fk}}',
            '{{%company}}'
        );
        $this->dropColumn('{{%company}}', 'created_at');
        $this->dropColumn('{{%company}}', 'updated_at');
        $this->dropColumn('{{%company}}', 'created_by');
        $this->dropColumn('{{%company}}', 'updated_by');
    }
}
