<?php

use yii\db\Migration;

/**
 * Class m210224_135548_customer_add_defaults
 */
class m210224_135548_customer_add_defaults extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $baseTable = 'customer';
        $this->addColumn("{{%$baseTable}}", 'rate_source', $this->string());
        $this->addColumn("{{%$baseTable}}", $column = 'acc_matrix_id', $this->integer());
        $this->addForeignKey(
            "{{%{$baseTable}_{$column}_fk}}",
            "{{%$baseTable}}",
            $column,
            "{{%accessorial_matrix}}",
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->addColumn("{{%$baseTable}}", 'discount', $this->decimal(10, 2));
        $this->addColumn("{{%$baseTable}}", 'system', $this->string());
        $this->addColumn("{{%$baseTable}}", 'calc_by', $this->string());
        $this->addColumn("{{%$baseTable}}", 'rating', $this->string());
        $this->addColumn("{{%$baseTable}}", 'agency', $this->string());
        $this->addColumn("{{%$baseTable}}", 'approved_by', $this->integer());
        $this->addColumn("{{%$baseTable}}", 'terms', $this->integer());
        $this->addColumn("{{%$baseTable}}", 'limit', $this->decimal(10, 2));
        $this->addColumn("{{%$baseTable}}", 'credit_hold', $this->boolean());
        $this->addColumn("{{%$baseTable}}", 'bond', $this->boolean());
        $this->addColumn("{{%$baseTable}}", 'expires', $this->date());
        $this->addColumn("{{%$baseTable}}", 'number', $this->string());
        $this->addColumn("{{%$baseTable}}", 'bank', $this->text());
        $this->addColumn("{{%$baseTable}}", 'salesman_1', $this->integer());
        $this->addColumn("{{%$baseTable}}", 'pay_type_1', $this->string());
        $this->addColumn("{{%$baseTable}}", 'rate_1', $this->decimal(10, 2));
        $this->addColumn("{{%$baseTable}}", 'salesman_2', $this->integer());
        $this->addColumn("{{%$baseTable}}", 'pay_type_2', $this->string());
        $this->addColumn("{{%$baseTable}}", 'rate_2', $this->decimal(10, 2));
        $this->addColumn("{{%$baseTable}}", 'mail_list', $this->boolean());
        $this->addColumn("{{%$baseTable}}", 'send_210', $this->boolean());
        $this->addColumn("{{%$baseTable}}", 'send_214', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $baseTable = 'customer';
        $this->dropColumn("{{%$baseTable}}", 'rate_source');
        $column = 'acc_matrix_id';
        $this->dropForeignKey(
            "{{%{$baseTable}_{$column}_fk}}",
            "{{%$baseTable}}"
        );
        $this->dropColumn("{{%$baseTable}}", $column);
        $this->dropColumn("{{%$baseTable}}", 'discount');
        $this->dropColumn("{{%$baseTable}}", 'system');
        $this->dropColumn("{{%$baseTable}}", 'calc_by');
        $this->dropColumn("{{%$baseTable}}", 'rating');
        $this->dropColumn("{{%$baseTable}}", 'agency');
        $this->dropColumn("{{%$baseTable}}", 'approved_by');
        $this->dropColumn("{{%$baseTable}}", 'terms');
        $this->dropColumn("{{%$baseTable}}", 'limit');
        $this->dropColumn("{{%$baseTable}}", 'credit_hold');
        $this->dropColumn("{{%$baseTable}}", 'bond');
        $this->dropColumn("{{%$baseTable}}", 'expires');
        $this->dropColumn("{{%$baseTable}}", 'number');
        $this->dropColumn("{{%$baseTable}}", 'bank');
        $this->dropColumn("{{%$baseTable}}", 'salesman_1');
        $this->dropColumn("{{%$baseTable}}", 'pay_type_1');
        $this->dropColumn("{{%$baseTable}}", 'rate_1');
        $this->dropColumn("{{%$baseTable}}", 'salesman_2');
        $this->dropColumn("{{%$baseTable}}", 'pay_type_2');
        $this->dropColumn("{{%$baseTable}}", 'rate_2');
        $this->dropColumn("{{%$baseTable}}", 'mail_list');
        $this->dropColumn("{{%$baseTable}}", 'send_210');
        $this->dropColumn("{{%$baseTable}}", 'send_214');
    }
}
