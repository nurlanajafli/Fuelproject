<?php

namespace common\models;

use Yii;
use \common\models\base\ServiceCode as BaseServiceCode;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "service_code".
 */
class ServiceCode extends BaseServiceCode
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
