<?php

namespace frontend\forms\samsara;
use yii\base\Model;
use Yii;

class TimeSelect extends Model
{
    public $startTime;
    public $endTime;

    public function rules() {
        return [
            [['startTime','endTime'], 'required'],
            [['startTime','endTime'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'startTime' => Yii::t('app', 'Start time from:'),
            'endTime' => Yii::t('app', 'To:'),
        ];
    }
}