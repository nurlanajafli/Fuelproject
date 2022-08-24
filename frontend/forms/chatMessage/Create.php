<?php

namespace frontend\forms\chatMessage;

use yii\base\Model;

class Create extends Model
{
    public $message;

    public function rules()
    {
        return [
            [['message'], 'trim'],
            [['message'], 'filter', 'filter' => 'strip_tags'],
            [['message'], 'required'],
        ];
    }
}