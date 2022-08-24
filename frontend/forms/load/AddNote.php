<?php

namespace frontend\forms\load;

use common\models\LoadNoteType;
use yii\base\Model;
use Yii;
use common\enums\I18nCategory;

class AddNote extends Model
{
    public $date;
    public $time;
    public $lastAction;
    public $notes;

    public function attributeLabels() {
        return [
            'date' => Yii::t('app', 'Date/Time'),
        ];
    }

    public function rules() {
        return [
            [['date', 'time', 'lastAction', 'notes'], 'required'],
            [['date'], 'match', 'pattern' => '/^\d{4}-\d{2}-\d{2}$/'],
            [['time'], 'match', 'pattern' => '/^\d{2}:\d{2}$/'],
            [['lastAction'], 'exist', 'skipOnError' => true, 'targetClass' => LoadNoteType::class, 'targetAttribute' => ['lastAction' => 'id']],
            [['notes'], 'string'],
        ];
    }
}