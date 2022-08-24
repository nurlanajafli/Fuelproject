<?php

use yii\db\Migration;

/**
 * Class m210601_103046_customer_add_terms_foreign_key
 */
class m210601_103046_customer_add_terms_foreign_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            '{{%customer_terms_fk}}',
            '{{%customer}}',
            'terms',
            '{{%payment_term_code}}',
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
            '{{%customer_terms_fk}}',
            '{{%customer}}'
        );
    }
}
