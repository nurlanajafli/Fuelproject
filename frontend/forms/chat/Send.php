<?php

namespace frontend\forms\chat;

use yii\base\Model;

class Send extends Model
{
    public $message;

    public function rules()
    {
        return [
            [['message'], 'string'],
            [['message'], 'trim'],
            [['message'], 'filter', 'filter' => 'strip_tags'],
            [['message'], 'required'],
        ];
    }
}
