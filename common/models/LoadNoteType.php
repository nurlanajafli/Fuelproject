<?php

namespace common\models;

use Yii;
use \common\models\base\LoadNoteType as BaseLoadNoteType;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "load_note_type".
 */
class LoadNoteType extends BaseLoadNoteType
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
