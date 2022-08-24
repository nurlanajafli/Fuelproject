<?php

namespace app\models;

use Yii;
use \app\models\base\FuelImport as BaseFuelImport;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "fuel_import".
 */
class FuelImport extends BaseFuelImport
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
