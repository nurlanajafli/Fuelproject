<?php

namespace common\models;

use Yii;
use \common\models\base\LogActivityCode as BaseLogActivityCode;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "log_activity_code".
 */
class LogActivityCode extends BaseLogActivityCode
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
