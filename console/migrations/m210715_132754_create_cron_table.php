<?php

use yii\db\Migration;
use common\enums\CronStatus;

/**
 * Handles the creation of table `{{%cron}}`.
 */
class m210715_132754_create_cron_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cron}}', [
            'id' => $this->primaryKey(),
            'task' => $this->string()->notNull(),
            'data' => $this->text(),
            'start_time' => $this->timestamp()->notNull(),
            'max_retry_count' => $this->integer()->notNull(),
            'attempts' => $this->integer()->notNull()->defaultValue(0),
            'next_attempt_interval' => $this->integer()->notNull(),
            'status' => $this->string()->notNull()->defaultValue(CronStatus::_NEW),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('LOCALTIMESTAMP'),
            'updated_at' => $this->timestamp()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cron}}');
    }
}
