<?php

namespace common\models;

use Yii;
use \common\models\base\LoadAccessory as BaseLoadAccessory;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "load_accessories".
 */
class LoadAccessory extends BaseLoadAccessory
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

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'matrix_id' => Yii::t('app', 'Matrix'),
            'accessorial_id' => Yii::t('app', 'Accessorials'),
        ]);
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
