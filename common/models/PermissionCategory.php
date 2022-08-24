<?php

namespace common\models;

use Yii;
use \common\models\base\PermissionCategory as BasePermissionCategory;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "permission_category".
 */
class PermissionCategory extends BasePermissionCategory
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
