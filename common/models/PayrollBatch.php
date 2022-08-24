<?php

namespace common\models;

use common\enums\PayrollBatchStatus;
use common\enums\PayrollBatchType;
use common\helpers\DateTime;
use common\models\base\PayrollBatch as BasePayrollBatch;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "payroll_batch".
 *
 * @property-read integer $posted
 * @property-read integer $unposted
 */
class PayrollBatch extends BasePayrollBatch
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['status'], 'in', 'range' => PayrollBatchStatus::getEnums()],
                [['type'], 'in', 'range' => PayrollBatchType::getEnums()],
            ]
        );
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => Yii::t('app', 'No')
        ]);
    }

    public function getPosted()
    {
        return count(array_filter($this->payrolls, function (Payroll $payroll) {
            return $payroll->posted;
        }));
    }

    public function getUnposted()
    {
        return count(array_filter($this->payrolls, function (Payroll $payroll) {
            return !$payroll->posted;
        }));
    }
}
