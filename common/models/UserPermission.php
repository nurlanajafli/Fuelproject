<?php

namespace common\models;

use Yii;
use \common\models\base\UserPermission as BaseUserPermission;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_permission".
 */
class UserPermission extends BaseUserPermission
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
