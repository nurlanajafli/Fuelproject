<?php


namespace frontend\forms\payroll;

use yii\base\Model;

class BatchSummary extends Model
{
    public $fieldIds;

    public function rules()
    {
        return [
            [['fieldIds'], 'each', 'rule' => ['integer']]
        ];
    }
}