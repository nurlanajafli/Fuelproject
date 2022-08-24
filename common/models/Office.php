<?php

namespace common\models;

use common\models\base\Office as BaseOffice;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "office".
 */
class Office extends BaseOffice
{
    public function get_label()
    {
        return $this->office;
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => Yii::t('app', 'No')
        ]);
    }
}
