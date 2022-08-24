<?php

namespace common\models;

use Yii;
use \common\models\base\LoadType as BaseLoadType;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "load_type".
 */
class LoadType extends BaseLoadType
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

    public function get_label()
    {
        return $this->description;
    }
}
