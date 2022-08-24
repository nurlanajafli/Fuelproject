<?php

namespace common\models;

use common\models\base\LoadNote as BaseLoadNote;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "load_note".
 */
class LoadNote extends BaseLoadNote
{
    public function behaviors() {
        return [
            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,
            ]
        ];
    }
}
