<?php

namespace common\models;

use Yii;
use \common\models\base\LocationContact as BaseLocationContact;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "location_contact".
 */
class LocationContact extends BaseLocationContact
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
