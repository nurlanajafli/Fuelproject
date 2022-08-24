<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Class m210419_101121_driver_add_user_id
 */
class m210419_101121_driver_add_user_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%driver}}', 'user_id', $this->integer()->unique());
        $this->addForeignKey(
            '{{%driver_user_id_fk}}',
            '{{%driver}}',
            'user_id',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
        $this->dropColumn('{{%user}}', 'username');
        $this->dropColumn('{{%driver}}', 'password_hash');
        $idx = $this->db->getSchema()->findUniqueIndexes($this->db->getTableSchema('{{%driver}}'));
        if (strpos(array_key_first($idx), 'driver_email_address_unique') !== false) {
            $this->dropIndex('{{%driver_email_address_unique}}', '{{%driver}}');
        }
        $this->alterColumn('{{%driver}}', 'email_address', $this->string());
        $this->alterColumn('{{%user}}', 'status', $this->smallInteger()->notNull()->defaultValue(1));
        $this->dropColumn('{{%user}}', 'created_at');
        $this->dropColumn('{{%user}}', 'updated_at');
        $this->addColumn('{{%user}}', 'created_at', 'timestamp with time zone');
        $this->addColumn('{{%user}}', 'updated_at', 'timestamp with time zone');
        $this->update('{{%user}}', [
            'created_at' => new Expression('current_timestamp'),
            'updated_at' => new Expression('current_timestamp')
        ]);
        $this->alterColumn('{{%user}}', 'created_at', 'timestamp with time zone NOT NULL');
        $this->alterColumn('{{%user}}', 'updated_at', 'timestamp with time zone NOT NULL');
        $this->update('{{%user}}', ['status' => 1], ['status' => 10]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%driver_user_id_fk}}',
            '{{%driver}}'
        );
        $this->dropColumn('{{%driver}}', 'user_id');
        $this->addColumn('{{%user}}', 'username', $this->string()->unique());
        $this->update('{{%user}}', ['username' => new Expression('email')]);
        $this->alterColumn('{{%user}}', 'username', $this->string()->notNull()->unique());
        $this->addColumn('{{%driver}}', 'password_hash', $this->string());
        $this->alterColumn('{{%driver}}', 'email_address', $this->string()->notNull()->unique());
        $this->alterColumn('{{%user}}', 'status', $this->smallInteger()->notNull()->defaultValue(10));
        $this->dropColumn('{{%user}}', 'created_at');
        $this->dropColumn('{{%user}}', 'updated_at');
        $this->addColumn('{{%user}}', 'created_at', $this->integer());
        $this->addColumn('{{%user}}', 'updated_at', $this->integer());
        $this->update('{{%user}}', ['created_at' => 1616850700, 'updated_at' => 1616850700]);
        $this->alterColumn('{{%user}}', 'created_at', $this->integer()->notNull());
        $this->alterColumn('{{%user}}', 'updated_at', $this->integer()->notNull());
        $this->update('{{%user}}', ['status' => 10], ['status' => 1]);
    }
}
