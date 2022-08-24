<?php

namespace common\models;

use \common\models\base\LoadMovement as BaseLoadMovement;
use common\helpers\DateTime;
use yii\helpers\ArrayHelper;
use common\enums\LoadMovementAction;

/**
 * This is the model class for table "load_movement".
 */
class LoadMovement extends BaseLoadMovement
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['action'], 'in', 'range' => LoadMovementAction::getEnums()]
        ]);
    }
}
