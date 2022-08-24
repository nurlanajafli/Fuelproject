<?php


namespace frontend\forms\payroll;
use common\enums\BatchConvertFormat;

use Yii;
use yii\base\Model;

/*
* @property integer $batch_id
* @property string $convertFormat
*/

class BatchConvert extends Model
{
    public $convertFormat;
    public $batch_id;

    public function rules()
    {
        return [
            [['convertFormat'], 'string'],
            [['batch_id'], 'integer'],
            [['convertFormat'], 'in', 'range' => BatchConvertFormat::getEnums()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'convertFormat' => Yii::t('app', 'Export format'),
            'batch_id' => Yii::t('app', 'Batch No'),
        ];
    }
}