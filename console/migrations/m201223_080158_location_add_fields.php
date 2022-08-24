<?php

use yii\db\Migration;

/**
 * Class m201223_080158_location_add_fields
 */
class m201223_080158_location_add_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%zone}}', [
            'code' => $this->string()->notNull(),
            'description' => $this->string()
        ]);
        $this->addPrimaryKey('{{%zone_code_pk}}', '{{%zone}}', 'code');
        $this->batchInsert('{{%zone}}', ['code', 'description'], [['IS', 'In State'], ['MW', 'Midwest US']]);
        $this->addColumn('{{%location}}', 'emergency', $this->string()->notNull()->after('main_fax'));
        $this->addColumn('{{%location}}', 'business_hours', $this->string()->after('emergency'));
        $this->addColumn('{{%location}}', 'zone', $this->string()->after('business_hours'));
        $this->addForeignKey('{{%location_zone_fk}}', '{{%location}}', 'zone', '{{%zone}}', 'code', 'RESTRICT', 'CASCADE');
        $this->addColumn('{{%location}}', 'bill_to', $this->integer()->after('website'));
        $this->addForeignKey('{{%location_bill_to_fk}}', '{{%location}}', 'bill_to', '{{%customer}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addColumn('{{%location}}', 'directions', $this->text()->after('notes'));
        $this->addColumn('{{%location}}', 'location', 'GEOMETRY(POINT,4326) NOT NULL');
        $this->createIndex('{{%location_location_idx}}', '{{%location}}', 'location', \yii\db\pgsql\QueryBuilder::INDEX_GIST);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%location_bill_to_fk}}', '{{%location}}');
        $this->dropForeignKey('{{%location_zone_fk}}', '{{%location}}');
        $this->dropIndex('{{%location_location_idx}}', '{{%location}}');
        $this->dropColumn('{{%location}}', 'emergency');
        $this->dropColumn('{{%location}}', 'business_hours');
        $this->dropColumn('{{%location}}', 'zone');
        $this->dropColumn('{{%location}}', 'bill_to');
        $this->dropColumn('{{%location}}', 'directions');
        $this->dropColumn('{{%location}}', 'location');
        $this->dropPrimaryKey('{{%zone_code_pk}}', '{{%zone}}');
        $this->dropTable('{{%zone}}');
    }
}
