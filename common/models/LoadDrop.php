<?php

namespace common\models;

use common\enums\TrailerDisposition;
use common\models\base\LoadDrop as BaseLoadDrop;
use Yii;
use yii\helpers\ArrayHelper;
use common\helpers\DateTime;

/**
 * This is the model class for table "load_drop".
 */
class LoadDrop extends BaseLoadDrop
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['trailer_disposition'], 'in', 'range' => TrailerDisposition::getEnums()]
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'drop_date' => Yii::t('app', 'Date'),
            'drop_time_from' => Yii::t('app', 'From'),
            'drop_time_to' => Yii::t('app', 'To'),
            'location_id' => Yii::t('app', 'Location'),
            'retrieve_date' => Yii::t('app', 'Date'),
            'retrieve_time' => Yii::t('app', 'Time'),
        ]);
    }
}
