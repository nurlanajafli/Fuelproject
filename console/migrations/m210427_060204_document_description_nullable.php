<?php

use yii\db\Migration;

/**
 * Class m210427_060204_document_description_nullable
 */
class m210427_060204_document_description_nullable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn($t = '{{%document}}', 'description', $this->string());
        $this->renameColumn($t, 'file_name', 'file');
        $this->addColumn($t, 'width', $this->integer());
        $this->addColumn($t, 'height', $this->integer());
        $this->addColumn($t, 'size', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($t = '{{%document}}', 'description', $this->string()->notNull());
        $this->renameColumn($t, 'file', 'file_name');
        $this->dropColumn($t, 'width');
        $this->dropColumn($t, 'height');
        $this->dropColumn($t, 'size');
    }
}
