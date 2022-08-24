<?php

namespace common\models;

use common\enums\TrailerStatus;
use common\enums\TruckStatus;
use common\enums\UnitItemStatus;
use common\helpers\DateTime;
use common\models\base\Unit as BaseUnit;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "unit".
 */
class Unit extends BaseUnit
{
    public function init()
    {
        parent::init();

        if ($this->isNewRecord) {
            $this->active = DateTime::nowDateYMD();
        }
    }

    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['truck_id'], 'unique'],
            [['trailer_id'], 'unique'],
            [['trailer_2_id'], 'unique'],
            [['driver_id'], 'unique'],
            [['co_driver_id'], 'unique'],
            [['driver_id'], function ($attribute, $params) {
                if (!$this->hasErrors($attribute) && ($this->getOldAttribute($attribute) != $this->$attribute) && !ArrayHelper::isIn($this->driver->status, [UnitItemStatus::AVAILABLE])) {
                    $this->addError($attribute, 'Already in use');
                }
            }],
            [['co_driver_id'], function ($attribute, $params) {
                if (!$this->hasErrors('driver_id') && $this->driver_id == $this->$attribute) {
                    $this->addError($attribute, 'Co Driver must not be the same as the Driver');
                }
                if (!$this->hasErrors($attribute) && ($this->getOldAttribute($attribute) != $this->$attribute) && !ArrayHelper::isIn($this->coDriver->status, [UnitItemStatus::AVAILABLE])) {
                    $this->addError($attribute, 'Already in use');
                }
            }],
            [['truck_id'], function ($attribute, $params) {
                if (!$this->hasErrors($attribute) && ($this->getOldAttribute($attribute) != $this->$attribute) && !ArrayHelper::isIn($this->truck->status, [TruckStatus::AVAILABLE])) {
                    $this->addError($attribute, 'Already in use');
                }
            }],
            [['trailer_id'], function ($attribute, $params) {
                if (!$this->hasErrors($attribute) && ($this->getOldAttribute($attribute) != $this->$attribute) && !ArrayHelper::isIn($this->trailer->status, [TrailerStatus::AVAILABLE])) {
                    $this->addError($attribute, 'Already in use');
                }
            }],
            [['trailer_2_id'], function ($attribute, $params) {
                if (!$this->hasErrors('trailer_id') && $this->trailer_id == $this->$attribute) {
                    $this->addError($attribute, 'Trailer 2 must not be the same as the Trailer');
                }
                if (!$this->hasErrors($attribute) && ($this->getOldAttribute($attribute) != $this->$attribute) && !ArrayHelper::isIn($this->trailer2->status, [TrailerStatus::AVAILABLE])) {
                    $this->addError($attribute, 'Already in use');
                }
            }],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'driver_id' => Yii::t('app', 'Driver'),
            'co_driver_id' => Yii::t('app', 'Co Driver'),
            'truck_id' => Yii::t('app', 'Truck'),
            'trailer_id' => Yii::t('app', 'Trailer'),
            'trailer_2_id' => Yii::t('app', 'Trailer 2'),
            'office_id' => Yii::t('app', 'Office'),
        ]);
    }
}
