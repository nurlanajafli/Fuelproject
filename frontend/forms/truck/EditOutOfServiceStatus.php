<?php

namespace frontend\forms\truck;

use yii\base\Model;

class EditOutOfServiceStatus extends Model
{

    const EDIT = 'edit';
    const REMOVE = 'remove';

    public $editOrRemoveDate;
    public $date;

    public function rules()
    {
        return [
            [['editOrRemoveDate'], 'required'],
            [['date'], 'required', 'when' => function ($model) {
                return $model->editOrRemoveDate == self::EDIT;
            }],
            [['editOrRemoveDate', 'date'], 'string'],
            [['editOrRemoveDate'], 'in', 'range' => [self::EDIT, self::REMOVE]],
        ];
    }
}