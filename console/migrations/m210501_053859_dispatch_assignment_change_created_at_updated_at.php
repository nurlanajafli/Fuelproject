<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Class m210501_053859_dispatch_assignment_change_created_at_updated_at
 */
class m210501_053859_dispatch_assignment_change_created_at_updated_at extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%dispatch_assignment}}', 'created_at');
        $this->dropColumn('{{%dispatch_assignment}}', 'updated_at');
        $this->addColumn('{{%dispatch_assignment}}', 'created_at', $this->timestamp());
        $this->addColumn('{{%dispatch_assignment}}', 'updated_at', $this->timestamp());
        $this->update('{{%dispatch_assignment}}', [
            'created_at' => new Expression('LOCALTIMESTAMP')
        ]);
        $this->alterColumn('{{%dispatch_assignment}}', 'created_at', $this->timestamp()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%dispatch_assignment}}', 'created_at');
        $this->dropColumn('{{%dispatch_assignment}}', 'updated_at');
        $this->addColumn('{{%dispatch_assignment}}', 'created_at', $this->integer());
        $this->addColumn('{{%dispatch_assignment}}', 'updated_at', $this->integer());
    }
}
