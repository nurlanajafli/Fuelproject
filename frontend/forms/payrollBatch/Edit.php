<?php

namespace frontend\forms\payrollBatch;

use common\models\PayrollBatch;
use Yii;
use yii\base\Model;

class Edit extends Model
{
    protected $_payrollBatch;

    public $batchDate;
    public $checkDate;
    public $periodStart;
    public $periodEnd;
    public $ids;

    public function rules()
    {
        return [
            [['batchDate', 'checkDate', 'periodStart', 'periodEnd'], 'required'],
            [['batchDate', 'checkDate', 'periodStart', 'periodEnd'], 'safe'],
            [['batchDate', 'checkDate', 'periodStart', 'periodEnd'], 'date', 'format' => Yii::$app->params['formatter']['date']['db']],
            [['ids'], 'default', 'value' => []],
            [['ids'], 'each', 'rule' => ['integer']]
        ];
    }

    public function getPayrollBatch(): PayrollBatch
    {
        return $this->_payrollBatch;
    }

    public function setPayrollBatch(PayrollBatch $payrollBatch): void
    {
        $this->_payrollBatch = $payrollBatch;
    }
}