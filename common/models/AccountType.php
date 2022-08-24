<?php

namespace common\models;

use Yii;
use \common\models\base\AccountType as BaseAccountType;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "account_type".
 */
class AccountType extends BaseAccountType
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

    public function get_label()
    {
        return $this->type;
    }
}
