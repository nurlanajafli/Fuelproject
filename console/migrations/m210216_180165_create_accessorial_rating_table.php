<?php

use common\enums\OnInvoice;
use common\enums\CalculateBy;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%accessorial_rating}}`.
 */
class m210216_180165_create_accessorial_rating_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('{{%accessorial_rating}}', [
			'id' => $this->primaryKey(),
			'accessorial_rating_matrix' => $this->integer()->notNull(),
			'description' => $this->string()->notNull(),
			'rate' => $this->decimal( 10,4)->defaultValue(0),
			'gl_account' => $this->integer()->notNull(),
			'calculate_by' => $this->string()->defaultValue(null),
			'on_invoice' => $this->string()->defaultValue(null),
			'pay_driver' => $this->boolean()->notNull()->defaultValue(true),
			'pay_comm' => $this->boolean()->notNull()->defaultValue(true),
			'auto' => $this->boolean()->notNull()->defaultValue(true),
			'inactive' => $this->boolean()->notNull()->defaultValue(false),
		]);

		$this->addForeignKey(
			'accessorial_rating_matrix_fk',
			'{{%accessorial_rating}}',
			'accessorial_rating_matrix',
			'{{%accessorial_matrix}}',
			'matrix_no',
			'CASCADE',
			'CASCADE',
		);

		$this->batchInsert('{{%accessorial_rating}}',
			['accessorial_rating_matrix', 'description', 'rate', 'gl_account', 'calculate_by', 'on_invoice', 'pay_driver', 'pay_comm', 'auto', 'inactive'],
			[
				[110, 'FSC-Percent', 0, 3010, CalculateBy::PERCENT, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'FSC-Miles', 0, 3010, CalculateBy::MILES, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'Lumper', 0, 3010, null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'Extra Stop', 0, 3010,  null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'Detention', 0, 3010,  null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'Tarping', 0, 3010,  null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'Redelivery', 0, 3010,  null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'Pallets', 0, 3010,  null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'Escort', 0, 3010,  null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'Permits', 0, 3010,  null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'Driver Load', 0, 3010,  null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'Driver Inload', 0, 3010,  null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'TONU', 0, 3010,  null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'LATE FEE', -250, 3010, null,OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'TRL REPO', 0, 3010,  null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'ADJUSTMENT', 0, 3010,  null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'STORAGE', 0, 3010,  null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'TOLL CHARGES', 0, 3010, null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'TONU TEAM', 325, 3010, null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'TONU SOLO', 225, 3010, null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'CAA-AD HOC TONU', 175, 3010, null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'Scale Ticket', 0, 3010, null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'Truck Wash', 0, 3010, null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'Layover', 0, 3010, null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'TRAILER REIMBURSE', 0, 3010, null, OnInvoice::ITEMIZE, true, false, false, false],
				[110, 'REIMBURSEMENT', 0, 3010, null, OnInvoice::ITEMIZE, true, false, false, false]
			]);

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {

		$this->dropForeignKey(
			'accessorial_rating_matrix_fk',
			'{{%accessorial_rating}}',
		);

		$this->dropTable('{{%accessorial_rating}}');
	}
}

