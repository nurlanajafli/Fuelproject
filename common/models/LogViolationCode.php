<?php

namespace common\models;

use Yii;
use \common\models\base\LogViolationCode as BaseLogViolationCode;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "log_violation_code".
 */
class LogViolationCode extends BaseLogViolationCode
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
