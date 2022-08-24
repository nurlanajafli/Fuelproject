<?php

namespace common\models;

use Yii;
use \common\models\base\CompanyNoteCode as BaseCompanyNoteCode;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "company_note_code".
 */
class CompanyNoteCode extends BaseCompanyNoteCode
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
