<?php

namespace common\models;

use common\enums\FuelCardType;
use common\helpers\DateTime;
use common\models\base\DriverFuelCard as BaseDriverFuelCard;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "driver_fuel_card".
 */
class DriverFuelCard extends BaseDriverFuelCard
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['card_type'], 'in', 'range' => FuelCardType::getEnums()],
                [
                    ['discount_recapture_gl_acct'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => Account::className(),
                    'targetAttribute' => ['discount_recapture_gl_acct' => 'account'],
                    'filter' => function ($query) {
                        /** @var Query $query */
                        $query->andWhere(['account_type' => array_map(function ($accountType) {
                            /** @var AccountType $accountType */
                            return $accountType->id;
                        }, AccountType::find()->where(['type' => ['Income', 'Other Income']])->all())]);
                    }
                ],
                [['discount_recapture_gl_acct'], 'default', 'value' => null]
            ]
        );
    }

    public function formName()
    {
        return parent::formName() . "[{$this->card_type}]";
    }
}
