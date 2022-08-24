<?php

namespace common\models;

use Yii;
use \common\models\base\Hazmat as BaseHazmat;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "hazmat".
 */
class Hazmat extends BaseHazmat
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
