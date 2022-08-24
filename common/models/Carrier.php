<?php

namespace common\models;

use Yii;
use \common\models\base\Carrier as BaseCarrier;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "carrier".
 */
class Carrier extends BaseCarrier
{
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'main_phone' => Yii::t('app', 'Phone'),
            'main_800' => Yii::t('app', '800'),
            'main_fax' => Yii::t('app', 'Fax'),
            'state_id' => Yii::t('app', 'State'),
            'ar_contact' => Yii::t('app', 'A/R Contact'),
        ]);
    }
}
