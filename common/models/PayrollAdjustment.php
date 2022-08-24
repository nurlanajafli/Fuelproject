<?php

namespace common\models;

use common\enums\AdjustmentCalcBy;
use \common\models\base\PayrollAdjustment as BasePayrollAdjustment;
use yii\helpers\ArrayHelper;
use common\helpers\DateTime;
use Yii;
use common\enums\PayrollBatchType;

/**
 * This is the model class for table "payroll_adjustment".
 */
class PayrollAdjustment extends BasePayrollAdjustment
{
    const SCENARIO_D_DRIVER = PayrollBatchType::D_DRIVER;

    public function init()
    {
        parent::init();

        $this->on(static::EVENT_AFTER_VALIDATE, [$this, 'postToKeepSingle']);
    }

    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['d_calc_by'], 'in', 'range' => AdjustmentCalcBy::getEnums(), 'on' => static::SCENARIO_D_DRIVER],
                [['d_payroll_adjustment_code', 'd_account'], 'default', 'value' => null, 'on' => static::SCENARIO_D_DRIVER],
                [['d_amount'], 'double', 'min' => 0, 'on' => static::SCENARIO_D_DRIVER]
            ]
        );
    }

    public function postToKeepSingle()
    {
        if (!$this->hasErrors()) {
            if ($this->d_post_to_carrier_id) {
                $this->d_post_to_driver_id = $this->d_post_to_vendor_id = null;
            } elseif ($this->d_post_to_driver_id) {
                $this->d_post_to_carrier_id = $this->d_post_to_vendor_id = null;
            } elseif ($this->d_post_to_vendor_id) {
                $this->d_post_to_driver_id = $this->d_post_to_carrier_id = null;
            }
        }
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'd_payroll_adjustment_code' => Yii::t('app', 'Adj Code'),
            'd_post_to_carrier_id' => Yii::t('app', 'Post To'),
            'd_post_to_driver_id' => Yii::t('app', 'Post To'),
            'd_post_to_vendor_id' => Yii::t('app', 'Post To'),
            'd_account' => Yii::t('app', 'Account'),
            'd_calc_by' => Yii::t('app', 'Calc By'),
            'd_amount' => Yii::t('app', 'Amount'),
            'd_load_id' => Yii::t('app', 'Load'),
            'd_description' => Yii::t('app', 'Description'),
        ]);
    }
}
