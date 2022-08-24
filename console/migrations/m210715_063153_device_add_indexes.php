<?php

use yii\db\Migration;

/**
 * Class m210715_063153_device_add_indexes
 */
class m210715_063153_device_add_indexes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropPrimaryKey('{{%device_id_pk}}', '{{%device}}');
        $this->addPrimaryKey('{{%device_pk}}', '{{%device}}', ['id', 'os']);
        $this->createIndex('{{%device_user_unique_index}}', '{{%device}}', ['id', 'os', 'user_id'], true);
        $this->dropColumn('{{%device}}', 'created_at');
        $this->dropColumn('{{%device}}', 'updated_at');
        $this->addColumn('{{%device}}', 'created_at', $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'));
        $this->addColumn('{{%device}}', 'updated_at', $this->timestamp());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%device_user_unique_index}}', '{{%device}}');
        $this->dropPrimaryKey('{{%device_pk}}', '{{%device}}');
        $this->addPrimaryKey('{{%device_id_pk}}', '{{%device}}', 'id');
        $this->dropColumn('{{%device}}', 'created_at');
        $this->dropColumn('{{%device}}', 'updated_at');
        $this->addColumn('{{%device}}', 'created_at', 'timestamp with time zone');
        $this->addColumn('{{%device}}', 'updated_at', 'timestamp with time zone');
    }
}
