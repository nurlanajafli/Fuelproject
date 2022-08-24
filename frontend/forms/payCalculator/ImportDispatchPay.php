<?php

namespace frontend\forms\payCalculator;

use yii\base\Model;

class ImportDispatchPay extends Model
{
    public $ids;

    public function rules()
    {
        return [
            [['ids'], 'default', 'value' => []],
            [['ids'], 'each', 'rule' => ['integer']]
        ];
    }
}