<?php

namespace common\models;

use Yii;
use \common\models\base\ClaimCode as BaseClaimCode;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "claim_code".
 */
class ClaimCode extends BaseClaimCode
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
}
