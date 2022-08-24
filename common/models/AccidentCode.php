<?php

namespace common\models;

use Yii;
use \common\models\base\AccidentCode as BaseAccidentCode;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "accident_code".
 */
class AccidentCode extends BaseAccidentCode
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
