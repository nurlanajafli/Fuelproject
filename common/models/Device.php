<?php

namespace common\models;

use \common\models\base\Device as BaseDevice;
use yii\helpers\ArrayHelper;
use common\helpers\DateTime;

/**
 * This is the model class for table "device".
 */
class Device extends BaseDevice
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
                [['os'], 'in', 'range' => array_values(\common\enums\OS::getEnums())]
            ]
        );
    }
}
