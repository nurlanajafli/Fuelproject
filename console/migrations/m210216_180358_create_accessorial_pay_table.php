<?php

use common\enums\BasisType;
use common\enums\CalculateBy;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%accessorial_pay}}`.
 */
class m210216_180358_create_accessorial_pay_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('{{%accessorial_pay}}', [
			'id' => $this->primaryKey(),
			'office_id' => $this->integer()->notNull(),
			'accessorial_rating_id' => $this->integer()->notNull(),
			'rate_each' => $this->decimal(2)->defaultValue(0),
			'calc_by' => $this->string()->defaultValue(null),
			'basis' => $this->string()->defaultValue(null),
			'inactive' => $this->boolean()->notNull()->defaultValue(false),
		]);

		$this->createIndex('accessorial_pay_idx_unique',
			'{{%accessorial_pay}}',
			['office_id', 'accessorial_rating_id'],
			true);

		$this->addForeignKey(
			'office_id_fk',
			'{{%accessorial_pay}}',
			'office_id',
			'{{%office}}',
			'id',
			'CASCADE',
			'CASCADE',
		);

		$this->addForeignKey(
			'accessorial_rating_id_fk',
			'{{%accessorial_pay}}',
			'accessorial_rating_id',
			'{{%accessorial_rating}}',
			'id',
			'CASCADE',
			'CASCADE',
		);

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {

		$this->dropIndex(
			'accessorial_pay_idx_unique',
			'{{%accessorial_pay}}',
		);

		$this->dropForeignKey(
			'office_id_fk',
			'{{%accessorial_pay}}',
		);

		$this->dropForeignKey(
			'accessorial_rating_id_fk',
			'{{%accessorial_pay}}',
		);

		$this->dropTable('{{%accessorial_pay}}');
	}
}
