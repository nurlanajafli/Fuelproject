<?php

namespace frontend\forms\driver;

use yii\base\Model;
use Yii;

class Register extends Model
{
    public $userUnderstands;

    public function rules()
    {
        return [
            [['userUnderstands'], 'required', 'requiredValue' => 1],
            [['userUnderstands'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'userUnderstands' => Yii::t('app', 'I Understand These Services as Described')
        ];
    }
}