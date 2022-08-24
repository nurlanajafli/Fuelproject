<?php

namespace common\models;

use common\helpers\DateTime;
use common\models\base\PayrollPay as BasePayrollPay;
use common\enums\PayrollBatchType;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "payroll_pay".
 */
class PayrollPay extends BasePayrollPay
{
    const SCENARIO_D_DRIVER = PayrollBatchType::D_DRIVER;

    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['d_load_id'], 'required', 'on' => static::SCENARIO_D_DRIVER],
            [['payroll_id', 'd_load_id'], 'unique', 'targetAttribute' => ['payroll_id', 'd_load_id'], 'on' => static::SCENARIO_D_DRIVER],
        ]);
    }
}
