<?php

namespace frontend\forms\load;

use common\enums\LoadsheetType;
use Yii;
use yii\base\Model;

class Loadsheet extends Model
{
    public $revenue;
    public $directions;
    public $stopNotes;

    public function rules() {
        return [
            [['revenue','directions','stopNotes'], 'required'],
            [['revenue','directions','stopNotes'], 'string'],
            [['revenue'], 'in', 'range' => LoadsheetType::getUiEnums()],
            [['revenue'], 'in', 'range' => LoadsheetType::getUiEnums()],
            [['revenue'], 'in', 'range' => LoadsheetType::getUiEnums()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'revenue' => Yii::t('app', 'Select'),
            'directions' => Yii::t('app', 'Select'),
            'stopNotes' => Yii::t('app', 'Select'),
        ];
    }
}