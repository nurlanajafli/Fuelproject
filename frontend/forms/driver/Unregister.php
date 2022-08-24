<?php

namespace frontend\forms\driver;

use yii\base\Model;

class Unregister extends Model
{
    public $bugFix;

    public function rules()
    {
        return [
            [['bugFix'], 'string']
        ];
    }
}