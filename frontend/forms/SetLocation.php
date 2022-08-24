<?php

namespace frontend\forms;

use Yii;
use yii\helpers\ArrayHelper;

class SetLocation extends \yii\base\Model
{
    public $date;
    public $location_id;
    public $zone;

    public function rules()
    {
        return [
            [['date', 'location_id'], 'required'],
            [['date'], 'date', 'format' => Yii::$app->params['formatter']['date']['db']],
            [['location_id'], 'exist', 'targetClass' => 'common\models\Location', 'targetAttribute' => ['location_id' => 'id']],
            [['zone'], 'exist', 'targetClass' => 'common\models\Zone', 'targetAttribute' => ['zone' => 'code']]
        ];
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'location_id' => Yii::t('app', 'Location')
        ]);
    }
}
