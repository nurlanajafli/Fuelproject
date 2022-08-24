<?php

namespace common\models;

use common\helpers\DateTime;
use common\models\base\Vendor as BaseVendor;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "vendor".
 */
class Vendor extends BaseVendor
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

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
