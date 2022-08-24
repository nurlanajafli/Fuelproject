<?php

namespace common\models;

use Yii;
use \common\models\base\CarrierRanking as BaseCarrierRanking;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "carrier_ranking".
 */
class CarrierRanking extends BaseCarrierRanking
{

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }
}
