<?php

namespace frontend\forms\payroll;

use yii\base\Model;

class PrintSettlements extends Model
{
    public $ids;

    public function rules()
    {
        return [
            [['ids'], 'required'],
            [['ids'], 'each', 'rule' => ['integer']]
        ];
    }
}