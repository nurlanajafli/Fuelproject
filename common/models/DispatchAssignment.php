<?php

namespace common\models;

use common\actions\FormProcessingAction;
use common\components\PCMiler;
use common\enums\PaySource;
use common\enums\PayType;
use common\enums\UnitItemStatus;
use common\models\base\DispatchAssignment as BaseDispatchAssignment;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "dispatch_assignment".
 */
class DispatchAssignment extends BaseDispatchAssignment
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_DISPATCH_RESERVED = 'dispatch';
    const SCENARIO_EDIT = 'edit';

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('CURRENT_TIMESTAMP')
            ],
            [
                'class' => BlameableBehavior::class,
            ]
        ];
    }

    public function init()
    {
        parent::init();

        $this->on(static::EVENT_BEFORE_INSERT, $func = function ($event) {
            /** @var DispatchAssignment $model */
            $model = $event->sender;
            if ($model->isAttributeChanged('unit_id')) {
                $emptyMiles = null;
                if ($model->unit_id) {
                    /** @var TrackingLog $row */
                    $row = TrackingLog::find()->
                    alias('t0')->
                    joinWith('location')->
                    andWhere(['unit_id' => $model->unit_id])->
                    orderBy(['t0.created_at' => SORT_DESC])->
                    one();
                    if ($model->load->loadStops[0]->company && $row && $row->location) {
                        /** @var PCMiler $pcmiler */
                        $pcmiler = Yii::$app->pcmiler;
                        $emptyMiles = $pcmiler->getDistance(
                            ['latitude' => $model->load->loadStops[0]->company->lat, 'longitude' => $model->load->loadStops[0]->company->lon],
                            ['latitude' => $row->location->lat, 'longitude' => $row->location->lon],
                        );
                    }
                }
                $model->driver_empty_miles = $emptyMiles;
                $model->codriver_empty_miles = $emptyMiles;
            }
        });
        $this->on(static::EVENT_BEFORE_UPDATE, $func);
        $this->on(FormProcessingAction::EVENT_AFTER_LOAD, $func);

        $this->on(FormProcessingAction::EVENT_AFTER_LOAD, $func = function ($event) {
            /** @var DispatchAssignment $model */
            $model = $event->sender;

            // Calculate rating by matrix
            if ($model->driver_pay_source == PaySource::MATRIX && $model->driver_pay_matrix != '') {
                $model->load->rating_matrix_id = $model->driver_pay_matrix;
                if ($model->load->ratingMatrix) {
                    $a = $model->load->ratingMatrix->calculate($model->load);
                    $model->driver_pay_type = $model->load->ratingMatrix->rate_type;
                    $model->driver_loaded_rate = $a['rate'];
                }
            }

            switch ($model->driver_pay_type) {
                case PayType::PERCENT:
                    $model->driver_total_pay = $model->driver ? $model->load->freight * floatval($model->driver->percentage) : 0;
                    break;
                case PayType::MILES:
                    $model->driver_total_pay = $model->driver_loaded_miles * floatval($model->driver_loaded_rate) + $model->driver_empty_miles * floatval($model->driver_empty_rate);
                    break;
                case PayType::FLAT:
                    $model->driver_total_pay = (empty($model->driver_loaded_rate) ? 0 : $model->driver_loaded_rate) - (empty($model->driver_empty_rate) ? 0 : $model->driver_empty_rate);
                    break;
            }
        });
        $this->on(static::EVENT_BEFORE_INSERT, $func);
        $this->on(static::EVENT_BEFORE_UPDATE, $func);
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['unit_id', 'driver_id', 'truck_id', 'trailer_id'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_EDIT]],
                ['unit_id', 'validateUnit', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_DISPATCH_RESERVED]],
                ['driver_id', 'validateDriver', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_DISPATCH_RESERVED]],
                ['truck_id', 'validateTruck', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_DISPATCH_RESERVED]],
                ['trailer_id', 'validateTrailer', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_DISPATCH_RESERVED]],
            ]
        );
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'driver_id' => Yii::t('app', 'Driver'),
            'codriver_id' => Yii::t('app', 'CoDriver'),
            'truck_id' => Yii::t('app', 'Truck'),
            'trailer_id' => Yii::t('app', 'Trailer'),
            'trailer2_id' => Yii::t('app', 'Trailer 2'),
            'driver_pay_source' => Yii::t('app', 'Pay Source'),
            'codriver_pay_source' => Yii::t('app', 'Pay Source'),
            'driver_pay_matrix' => Yii::t('app', 'Pay Matrix'),
            'codriver_pay_matrix' => Yii::t('app', 'Pay Matrix'),
            'driver_pay_type' => Yii::t('app', 'Pay Type'),
            'codriver_pay_type' => Yii::t('app', 'Pay Type'),
            'driver_loaded_miles' => Yii::t('app', 'Loaded Miles'),
            'codriver_loaded_miles' => Yii::t('app', 'Loaded Miles'),
            'driver_empty_miles' => Yii::t('app', 'Empty Miles'),
            'codriver_empty_miles' => Yii::t('app', 'Empty Miles'),
            'driver_loaded_rate' => Yii::t('app', 'Loaded Rate'),
            'codriver_loaded_rate' => Yii::t('app', 'Loaded Rate'),
            'driver_empty_rate' => Yii::t('app', 'Empty Rate'),
            'codriver_empty_rate' => Yii::t('app', 'Empty Rate'),
        ]);
    }

    public function validateUnit($attribute, $params)
    {
        $unit = Unit::findOne($this->$attribute);
        if ($unit->status != UnitItemStatus::AVAILABLE)
            $this->addError($attribute, 'Specified Unit is not Available');
    }

    public function validateDriver($attribute, $params)
    {
        $driver = Driver::findOne($this->$attribute);

        $load = Load::findOne($this->load_id);
        $latestDeliveryDate = date("Y-m-d", time());
        foreach ($load->loadStops as $stop) {
            if ($stop->available_thru) {
                $stopDate = date('Y-m-d H:i:s', strtotime($stop->available_thru));
                if ($stopDate > $latestDeliveryDate)
                    $latestDeliveryDate = $stopDate;
            }
        }

        if ($driver->status != UnitItemStatus::AVAILABLE)
            $this->addError($attribute, 'Specified Driver is not Available');

        $driverCompliance = $driver->driverCompliance;

        $cdlExpDate = $driverCompliance->cdl_expires;
        if (empty($cdlExpDate))
            $this->addError($attribute, 'Driver CDL expiration date required');
        elseif (strtotime($cdlExpDate) < time())
            $this->addError($attribute, 'Driver CDL has been expired');

        $nextPhysExam = $driverCompliance->next_dot_physical;
        if (empty($nextPhysExam))
            $this->addError($attribute, 'Driver Next DOT Physical date required');
        elseif (strtotime($cdlExpDate) < time())
            $this->addError($attribute, 'Driver Next DOT has been expired');

        if (!empty($this->codriver_id)) {
            $coDriver = Driver::findOne($this->codriver_id);
            if ($coDriver->status != UnitItemStatus::AVAILABLE)
                $this->addError('codriver_id', 'Specified CoDriver is not Available');

            $coDriverCompliance = $coDriver->driverCompliance;

            $coDriverCdlExpDate = $coDriverCompliance->cdl_expires;
            if (empty($coDriverCdlExpDate))
                $this->addError('codriver_id', 'CoDriver CDL expiration date required');
            elseif (strtotime($coDriverCdlExpDate) < time())
                $this->addError('codriver_id', 'CoDriver CDL has been expired');

            $coDriverNextPhysExam = $coDriverCompliance->next_dot_physical;
            if (empty($coDriverNextPhysExam))
                $this->addError('codriver_id', 'CoDriver Next DOT Physical date required');
            elseif (strtotime($coDriverNextPhysExam) < time())
                $this->addError('codriver_id', 'CoDriver Next DOT has been expired');
        }
    }

    public function validateTruck($attribute, $params)
    {
        $truck = Truck::findOne($this->$attribute);

        if ($truck->status != UnitItemStatus::AVAILABLE)
            $this->addError($attribute, 'Specified Truck is not Available');
    }

    public function validateTrailer($attribute, $params)
    {
        $trailer = Trailer::findOne($this->$attribute);

        if ($trailer->status != UnitItemStatus::AVAILABLE)
            $this->addError($attribute, 'Specified Trailer is not Available');

        if ($this->trailer2_id) {
            $trailer2 = Trailer::findOne($this->trailer2_id);

            if ($trailer2->status != UnitItemStatus::AVAILABLE)
                $this->addError('trailer2_id', 'Specified Trailer is not Available');
        }
    }
}
