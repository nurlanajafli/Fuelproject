<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%accessorial_matrix}}`.
 */
class m210216_180131_create_accessorial_matrix_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{%accessorial_matrix}}', [
			'id' => $this->primaryKey(),
			'matrix_no'=>$this->integer()->unique(),
			'matrix_name'=>$this->string()->notNull(),
			'inactive'=>$this->boolean()->notNull()->defaultValue(false),
		]);
		$this->batchInsert('{{%accessorial_matrix}}', ['matrix_no', 'matrix_name', 'inactive'], [
			[110, 3010, false],
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%accessorial_matrix}}');
	}
}
