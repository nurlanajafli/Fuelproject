<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use common\enums\I18nCategory;
use common\models\CompanyNoteCode;

class CustomerContactNote extends Model
{
    public $contact;
    public $code;
    public $notes;
    public $next_contact_date;
    public $next_contact_time;
    public $post_reminder;

    public function rules()
    {
        return [
            [['contact', 'post_reminder'], 'required'],
            [['notes'], 'string'],
            [['post_reminder'], 'boolean'],
            [['contact', 'code', 'next_contact_date', 'next_contact_time'], 'string', 'max' => 255],
            [['code'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyNoteCode::className(), 'targetAttribute' => ['code' => 'code']],
            [['next_contact_date'], 'match', 'pattern' => '/^\d{4}-\d{2}-\d{2}$/'],
            [['next_contact_time'], 'match', 'pattern' => '/^\d{2}:\d{2}$/'],
        ];
    }

    /**
     * @return \common\models\CustomerContactNote
     */
    public function getModel()
    {
        $model = new \common\models\CustomerContactNote();
        $model->contact = $this->contact;
        $model->code = $this->code;
        $model->notes = $this->notes;
        if ($this->next_contact_date && $this->next_contact_time) {
            $model->next_contact = "{$this->next_contact_date} {$this->next_contact_time}:00";
        }
        $model->post_reminder = $this->post_reminder;
        return $model;
    }

    public function attributeLabels()
    {
        return [
            'contact' => Yii::t('app', 'Contact'),
            'code' => Yii::t('app', 'Code'),
            'notes' => Yii::t('app', 'Notes'),
            'next_contact' => Yii::t('app', 'Next Contact'),
            'post_reminder' => Yii::t('app', 'Post Reminder?'),
        ];
    }
}