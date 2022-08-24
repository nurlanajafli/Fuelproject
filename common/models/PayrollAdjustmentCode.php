<?php

namespace common\models;

use Yii;
use \common\models\base\PayrollAdjustmentCode as BasePayrollAdjustmentCode;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "payroll_adjustment_code".
 */
class PayrollAdjustmentCode extends BasePayrollAdjustmentCode
{

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }
}
