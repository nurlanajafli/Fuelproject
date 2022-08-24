<?php

namespace common\models;

use Yii;
use \common\models\base\Cap as BaseCap;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cap".
 */
class Cap extends BaseCap
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
