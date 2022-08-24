<?php

namespace common\models;

use Yii;
use \common\models\base\BusinessDirection as BaseBusinessDirection;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "business_direction".
 */
class BusinessDirection extends BaseBusinessDirection
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
