<?php

namespace common\models;

use common\helpers\DateTime;
use common\models\base\FuelPurchase as BaseFuelPurchase;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "fuel_purchase".
 */
class FuelPurchase extends BaseFuelPurchase
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['quantity', 'cost', 'ppg'], 'number', 'min' => 0]
            ]
        );
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'unit_id' => Yii::t('app', 'Unit'),
            'driver_id' => Yii::t('app', 'Driver'),
            'codriver_id' => Yii::t('app', 'Co Driver'),
            'truck_id' => Yii::t('app', 'Truck'),
            'trailer_id' => Yii::t('app', 'Trailer'),
            'trailer2_id' => Yii::t('app', 'Trailer 2'),
            'state_id' => Yii::t('app', 'State'),
            'ppg' => Yii::t('app', 'PPG'),
        ]);
    }
}
