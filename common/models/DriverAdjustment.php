<?php

namespace common\models;

use common\enums\AdjustmentCalcBy;
use common\helpers\DateTime;
use common\models\base\DriverAdjustment as BaseDriverAdjustment;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "driver_adjustment".
 *
 * @property-read string $percent
 */
class DriverAdjustment extends BaseDriverAdjustment
{
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
        return ArrayHelper::merge(parent::rules(), [
            [['payroll_adjustment_code', 'account'], 'default', 'value' => null],
            [['calc_by'], 'in', 'range' => AdjustmentCalcBy::getEnums()],
            [['amount'], 'double', 'min' => 0]
        ]);
    }

    public function postToKeepSingle()
    {
        if (!$this->hasErrors()) {
            if ($this->post_to_carrier_id) {
                $this->post_to_driver_id = $this->post_to_vendor_id = null;
            } elseif ($this->post_to_driver_id) {
                $this->post_to_carrier_id = $this->post_to_vendor_id = null;
            } elseif ($this->post_to_vendor_id) {
                $this->post_to_driver_id = $this->post_to_carrier_id = null;
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'payroll_adjustment_code' => Yii::t('app', 'Adj Code'),
            'post_to_carrier_id' => Yii::t('app', 'Post To'),
            'post_to_driver_id' => Yii::t('app', 'Post To'),
            'post_to_vendor_id' => Yii::t('app', 'Post To'),
            'account' => Yii::t('app', 'Account'),
            'calc_by' => Yii::t('app', 'Calc By'),
            'amount' => Yii::t('app', 'Amount'),
            'cap_id' => Yii::t('app', 'Cap'),
            'truck_id' => Yii::t('app', 'Truck No')
        ];
    }

    public function getPercent()
    {
        return $this->calc_by == AdjustmentCalcBy::PERCENTAGE ? $this->amount : 0;
    }
}
