<?php

use yii\db\Migration;

/**
 * Class m220520_090115_alter_table_fuel_import_modify_column_product_code
 */
class m220520_090115_alter_table_fuel_import_modify_column_product_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        print $this->integer();
        $this->alterColumn('{{%fuel_import}}', 'product_code', 'integer USING CAST(product_code AS integer)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}
