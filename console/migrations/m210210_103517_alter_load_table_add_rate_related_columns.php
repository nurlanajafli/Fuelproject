<?php

use yii\db\Migration;

/**
 * Class m210210_103517_alter_load_table_add_rate_related_columns
 */
class m210210_103517_alter_load_table_add_rate_related_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%load}}', 'rate_source', $this->string(20)->defaultValue(common\enums\RateSource::MANUAL));
        $this->addColumn('{{%load}}', 'rate_by', $this->string(10)->defaultValue(common\enums\RateBy::FLAT));
        $this->addColumn('{{%load}}', 'rate', $this->decimal(10, 2)->defaultValue(0));
        $this->addColumn('{{%load}}', 'units', $this->decimal(10, 2)->defaultValue(0));
        $this->addColumn('{{%load}}', 'discount_percent', $this->decimal(5, 2)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%load}}', 'discount_percent');
        $this->dropColumn('{{%load}}', 'units');
        $this->dropColumn('{{%load}}', 'rate');
        $this->dropColumn('{{%load}}', 'rate_by');
        $this->dropColumn('{{%load}}', 'rate_source');
    }
}
