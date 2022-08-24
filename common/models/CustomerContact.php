<?php

namespace common\models;

use Yii;
use \common\models\base\CustomerContact as BaseCustomerContact;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "customer_contact".
 */
class CustomerContact extends BaseCustomerContact
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

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'department_id' => Yii::t('app', 'Department'),
            'desc_1' => Yii::t('app', 'Desc'),
            'desc_2' => Yii::t('app', 'Desc'),
        ]);
    }
}
