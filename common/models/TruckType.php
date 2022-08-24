<?php

namespace common\models;

use Yii;
use \common\models\base\TruckType as BaseTruckType;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "truck_type".
 */
class TruckType extends BaseTruckType
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

    public function get_label()
    {
        return $this->type . " - " . $this->description;
    }
}
