<?php

namespace common\models;

use common\enums\DriverType;
use common\enums\PayrollBatchType;
use common\models\base\Payroll as BasePayroll;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "payroll".
 *
 * @property-read string $totalwages
 * @property-read string $additions
 * @property-read string $deductions
 * @property-read string $netamount
 */
class Payroll extends BasePayroll
{
    const SCENARIO_D_DRIVER = PayrollBatchType::D_DRIVER;

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['driver_type'], 'in', 'range' => DriverType::getEnums()],
                [['driver_id'], 'unique', 'targetAttribute' => ['payroll_batch_id', 'driver_id'], 'on' => static::SCENARIO_D_DRIVER],
                [['driver_id'], 'required', 'on' => static::SCENARIO_D_DRIVER],
                [['bank_account', 'd_expense_acct'], 'default', 'value' => null]
            ]
        );
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'cd' => Yii::t('app', 'CD'),
            'office_id' => Yii::t('app', 'Off'),
            'driver_type' => Yii::t('app', 'COS'),
            'direct_deposit' => Yii::t('app', 'DD'),
            'ot_hours' => Yii::t('app', 'OT Hours'),
            'ot_2_hours' => Yii::t('app', 'OT 2 Hours'),
            'st_rate' => Yii::t('app', 'ST Rate'),
            'ot_rate' => Yii::t('app', 'OT Rate'),
            'ot_2_rate' => Yii::t('app', 'OT 2 Rate'),
            'totalwages' => Yii::t('app', 'Total Wages'),
            'additions' => Yii::t('app', 'Additions'),
            'deductions' => Yii::t('app', 'Deductions'),
            'netamount' => Yii::t('app', 'Net Amount'),
        ]);
    }

    public function calcPays()
    {
        $this->dispatch_pay = 0;
        $this->codriver_pay = 0;
        if ($this->payrollBatch->type == PayrollBatchType::D_DRIVER) {
            /** @var PayrollPay[] $payrollPays */
            $payrollPays = $this->getPayrollPays()->joinWith('dLoad.dispatchAssignment')->all();
            foreach ($payrollPays as $payrollPay) {
                if($payrollPay->dLoad->dispatchAssignment->driver->co_driver_id == '' || $payrollPay->dLoad->dispatchAssignment->driver->co_driver_id == 0
                    || is_null($payrollPay->dLoad->dispatchAssignment->driver->co_driver_id)) {
                    $this->dispatch_pay += $payrollPay->dLoad->dispatchAssignment->driver_total_pay;
                } else {
                    if($this->driver_id == $payrollPay->dLoad->dispatchAssignment->driver->co_driver_id) {
                        //return $load->dispatchAssignment->driver->co_driver_earning_percent;
                        $this->dispatch_pay += $payrollPay->dLoad->dispatchAssignment->driver_total_pay*$payrollPay->dLoad->dispatchAssignment->driver->co_driver_earning_percent;
                    } else {
                        $this->dispatch_pay += $payrollPay->dLoad->dispatchAssignment->driver_total_pay*(1-$payrollPay->dLoad->dispatchAssignment->driver->co_driver_earning_percent);
                    }
                }
            }
        }
    }

    public function calcOutcomeFields()
    {
        $result = [];
        $ot_hours = (is_numeric($this->ot_hours) ? $this->ot_hours : 0);
        $ot_2_hours = (is_numeric($this->ot_2_hours) ? $this->ot_2_hours : 0);
        $result['a'] = ((is_numeric($this->total_hours) ? $this->total_hours : 0)
                - $ot_hours
                - $ot_2_hours) * (is_numeric($this->st_rate) ? $this->st_rate : 0);
        $result['b'] = $ot_hours * (is_numeric($this->ot_rate) ? $this->ot_rate : 0);
        $result['c'] = $ot_2_hours * (is_numeric($this->ot_2_rate) ? $this->ot_2_rate : 0);
        $result['d'] = $result['a'] + $result['b'] + $result['c'];
        $result['ap'] = Yii::$app->formatter->asDecimal($result['a']);
        $result['bp'] = Yii::$app->formatter->asDecimal($result['b']);
        $result['cp'] = Yii::$app->formatter->asDecimal($result['c']);
        $result['dp'] = Yii::$app->formatter->asDecimal($result['d']);
        return $result;
    }

    public function getTotalWages()
    {
        $array = $this->calcOutcomeFields();
        return (is_numeric($this->salary_amount) ? $this->salary_amount : 0)
            + $array['d']
            + (is_numeric($this->other_pay_amount) ? $this->other_pay_amount : 0);
    }

    public function getAdditions()
    {
        return 0;
    }

    public function getDeductions()
    {
        if ($this->payrollBatch->type == PayrollBatchType::D_DRIVER) {
            return $this->getPayrollAdjustments()->sum('d_amount');
        }
        return 0;
    }

    public function getPeriodStart() {
        if($this->payrollBatch->period_start) {
            return Yii::$app->formatter->asDate($this->payrollBatch->period_start, Yii::$app->params['formats'][3]);
        }
        return 0;
    }

    public function getPeriodEnd() {
        if($this->payrollBatch->period_end) {
            return Yii::$app->formatter->asDate($this->payrollBatch->period_end, Yii::$app->params['formats'][3]);
        }
        return 0;
    }

    public function getCheckDate() {
        if($this->payrollBatch->check_date) {
            return Yii::$app->formatter->asDate($this->payrollBatch->check_date, Yii::$app->params['formats'][3]);
        }
        return 0;
    }

    public function getBatchType() {
        if($this->payrollBatch->type) {
            return $this->payrollBatch->type;
        }
        return 0;
    }

    public function getBatchNo() {
        if($this->payrollBatch->id) {
            return $this->payrollBatch->id;
        }
        return 0;
    }

    public function getNetAmount()
    {
        return $this->totalwages + $this->additions - $this->deductions;
    }

    public function getPayrollFor(): string
    {
        if ($this->driver_id) {
            return $this->driver->getFullName();
        }

        return '';
    }

    public function getPayableTo(): string
    {
        if ($this->pay_to_carrier_id) {
            return $this->payToCarrier->name;
        }

        if ($this->pay_to_driver_id) {
            return $this->payToDriver->getFullName();
        }

        if ($this->pay_to_vendor_id) {
            return $this->payToVendor->name;
        }

        if ($this->driver_id) {
            return $this->driver->getFullName();
        }

        return '';
    }

    public function getCOS(): string
    {
        return $this->driver_type;
    }
}
