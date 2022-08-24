<?php

namespace common\models;

use Yii;
use \common\models\base\LoadAccessories as BaseLoadAccessories;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "load_accessories".
 */
class LoadAccessories extends BaseLoadAccessories
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
