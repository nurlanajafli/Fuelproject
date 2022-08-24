<?php

namespace frontend\forms\document;

use yii\base\Model;
use common\models\DocumentCode;
use yii\helpers\ArrayHelper;
use Yii;

class Upload extends Model
{
    const SCENARIO_LOAD = 'load';

    public $description;
    public $code;
    public $fakeFile;

    public function rules()
    {
        return [
            [['description'], 'required', 'on' => static::SCENARIO_DEFAULT],
            [['code'], 'required', 'on' => static::SCENARIO_LOAD],
            [['description', 'code'], 'string', 'max' => 255],
            [['code'], 'exist', 'targetClass' => DocumentCode::class, 'targetAttribute' => ['code' => 'code'], 'on' => static::SCENARIO_LOAD],
            [['fakeFile'], function ($attribute, $params, $validator, $current) {
                if ($this->$attribute)
                    $this->addError($attribute, $this->$attribute);
            }]
        ];
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'code' => Yii::t('app', 'Image')
        ]);
    }
}