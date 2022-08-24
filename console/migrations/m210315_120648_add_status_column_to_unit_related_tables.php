<?php

use common\enums\UnitItemStatus;
use yii\db\Migration;

/**
 * Class m210315_120648_add_status_column_to_unit_related_tables
 */
class m210315_120648_add_status_column_to_unit_related_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("{{%unit}}", 'status', $this->string(25)->defaultValue(UnitItemStatus::AVAILABLE));
        $this->addColumn("{{%driver}}", 'status', $this->string(25)->defaultValue(UnitItemStatus::AVAILABLE));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%unit}}", 'status');
        $this->dropColumn("{{%driver}}", 'status');
    }
}
