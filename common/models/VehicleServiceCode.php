<?php

namespace common\models;

use common\enums\VehicleServiceEquip;
use Yii;
use \common\models\base\VehicleServiceCode as BaseVehicleServiceCode;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "vehicle_service_code".
 */
class VehicleServiceCode extends BaseVehicleServiceCode
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
                ['equip', 'in', 'range' => array_values(VehicleServiceEquip::getEnums())]
            ]
        );
    }
}
