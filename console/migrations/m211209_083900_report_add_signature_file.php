<?php

use yii\db\Migration;

/**
 * Class m211209_083900_report_add_signature_file
 */
class m211209_083900_report_add_signature_file extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%report}}', 'signature_file', $this->string());
        $this->addColumn('{{%report}}', 'signature_mime_type', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%report}}', 'signature_file');
        $this->dropColumn('{{%report}}', 'signature_mime_type');
    }
}
