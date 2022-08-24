<?php

namespace common\models;

use common\components\PCMiler;
use common\enums\LoadStopStatus;
use common\enums\LoadStopType;
use common\helpers\DateTime;
use common\helpers\EventHelper;
use common\models\base\LoadStop as BaseLoadStop;
use common\models\traits\Template;
use Yii;
use yii\base\Event;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "load_stop".
 */
class LoadStop extends BaseLoadStop
{
    use Template;

    public function init()
    {
        parent::init();

        $this->on(self::EVENT_BEFORE_INSERT, function (Event $event) {
            /** @var LoadStop $model */
            $model = $event->sender;
            $model->stop_number = self::find()->where(['load_id' => $model->load_id])->max('stop_number') + 1;
        });
        $this->on(static::EVENT_AFTER_INSERT, [$this, 'postSave']);
        $this->on(static::EVENT_AFTER_UPDATE, [$this, 'postSave']);
        $this->on(static::EVENT_BEFORE_DELETE, function (Event $event) {
            /** @var LoadStop $model */
            $model = $event->sender;

            Yii::$app->db->createCommand(
                sprintf('UPDATE %s SET stop_number=stop_number-1 WHERE load_id=%d AND stop_number>%d', self::tableName(), $model->load_id, $model->stop_number)
            )->execute();
            $nm = $model->getNeighboringModels();
            if ($nm[0]) {
                $nm[0]->calcMilesToNext($nm[1]);
            }
        });
    }

    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    const DEFAULT_FROM_TIME = "9:00"; // TODO move to params ?

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                ['company_id', 'required', 'when' => function($model) { return empty($model->address); }],
                ['address', 'required', 'when' => function($model) { return empty($model->company); }],
                ['city', 'required', 'when' => function($model) { return empty($model->company); }],
                ['zip', 'required', 'when' => function($model) { return empty($model->company); }],
                [['stop_type'], 'in', 'range' => LoadStopType::getEnums()],
                [['zone'], 'default', 'value' => null],
                [['status'], 'in', 'range' => LoadStopStatus::getEnums()]
            ]
        );
    }

    protected static function postSave(Event $event)
    {
        /** @var LoadStop $model */
        $model = $event->sender;
        if (
            (($event->name == static::EVENT_AFTER_INSERT) || EventHelper::attributeIsChanged($event, 'company_id')) &&
            ($p = $model->getNeighboringModels()[0])
        ) {
            $p->calcMilesToNext($model);
        }
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'company_id' => Yii::t('app', 'Company'),
            'state_id' => Yii::t('app', 'State'),
            'appt_reference' => Yii::t('app', 'Appt. Ref.'),
        ]);
    }

    /**
     * @param LoadStop|null $model
     */
    public function calcMilesToNext($model)
    {
        /** @var PCMiler $pcmiler */
        $pcmiler = Yii::$app->pcmiler;
        $this->miles_to_next = $pcmiler->getDistance(self::getPoint($this),self::getPoint($model));
        $this->save();
    }

    public function getCompanyName() {
        if($this->company_id) {
           return $this->company->company_name;
        } elseif($this->company_name) {
            return $this->company_name;
        }
        return null;
    }

    public function getAddress() {
        if($this->company_id) {
            return $this->company->address;
        } elseif($this->address) {
            return $this->address;
        }
        return null;
    }

    public function getCity() {
        if($this->company_id) {
            return $this->company->city;
        } elseif($this->city) {
            return $this->city;
        }
        return null;
    }

    public function getStateCode() {
        if($this->company_id) {
            return $this->company->state->state_code;
        } elseif($this->state) {
            return $this->state->state_code;
        }
        return null;
    }

    public function getZip() {
        if($this->company_id) {
            return $this->company->zip;
        } elseif($this->zip) {
            return $this->zip;
        }
        return null;
    }


    public static function getPoint(LoadStop $model, int $format = Location::POINT_FORMAT_LONG) {
        if($model->company) {
            return $model->company->getPoint($format);
        } elseif($model->lat && $model->lon) {
            switch ($format) {
                case Location::POINT_FORMAT_LONG:
                    return ['longitude'=>$model->lon, 'latitude'=>$model->lat];
                case Location::POINT_FORMAT_SHORT:
                    return ['Lon'=>$model->lon, 'Lat'=>$model->lat];
            }
        } else {
            return 0;
        }
    }

    public function getLocalFromTime()
    {
        $stopDefaultFromTime = self::DEFAULT_FROM_TIME;
        $tz = $this->company->time_zone;
        $fromDate = $this->available_from;
        $fromTime = empty($this->time_from) ? $stopDefaultFromTime : $this->time_from;
        return DateTime::fromTzToLocal($tz, $fromDate, $fromTime);
    }

    public function getMinutesLeftTillNow()
    {
        $stopDefaultFromTime = self::DEFAULT_FROM_TIME;
        $tz = $this->company->time_zone;
        $fromDate = $this->available_from;
        $fromTime = empty($this->time_from) ? $stopDefaultFromTime : $this->time_from;
        return DateTime::minutesLeftTillNowFromTzToLocal($tz, $fromDate, $fromTime);
    }

    public function getNeighboringModels()
    {
        $result = [null, null];
        /** @var LoadStop[] $rows */
        $rows = $this->load->getLoadStops()->orderBy('stop_number')->
        andWhere(['stop_number' => [$this->stop_number - 1, $this->stop_number + 1]])->andWhere(['<>', 'id', $this->id])->limit(2)->all();
        $i = count($rows);
        if ($i == 2)
            return $rows;

        if ($i == 1)
            $result = ($rows[0]->stop_number == $this->stop_number - 1) ? [$rows[0], null] : [null, $rows[0]];
        return $result;
    }
}
