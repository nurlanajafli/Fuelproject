<?php

namespace frontend\forms\load;

use yii\base\Model;
use Yii;

class Arrival extends Model
{
    const KEEP = 'keep';
    const DROP = 'drop';
    const LEAVE = 'leave';
    const CHECK = 'check';

    public $date;
    public $in;
    public $out;
    public $signedForBy;
    public $trailerDisposition;
    public $postDeliveryOptions;

    public function getTrailerOptions()
    {
        return [
            self::KEEP => Yii::t('app', 'Keep With Unit'),
            self::DROP => Yii::t('app', 'Drop At Consignee')
        ];
    }

    public function getUnitOptions()
    {
        return [
            self::LEAVE => Yii::t('app', 'Leave Unit At Consignee'),
            self::CHECK => Yii::t('app', 'Check Unit Schedule')
        ];
    }

    public function rules()
    {
        return [
            [['date', 'in', 'out', 'trailerDisposition', 'postDeliveryOptions'], 'required'],
            [['date'], 'date', 'format' => Yii::$app->params['formatter']['date']['db']],
            [['in', 'out'], 'time', 'format' => Yii::$app->params['formatter']['time']['24h']],
            [['signedForBy', 'trailerDisposition', 'postDeliveryOptions'], 'string'],
            [['trailerDisposition'], 'in', 'range' => array_keys($this->getTrailerOptions())],
            [['postDeliveryOptions'], 'in', 'range' => array_keys($this->getUnitOptions())]
        ];
    }
}