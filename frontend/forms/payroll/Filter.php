<?php

namespace frontend\forms\payroll;

use common\enums\PayrollBatchType;
use common\helpers\DateTime;
use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class Filter extends Model
{
    public $batchId;
    public $batchType;
    public $officeId;
    public $from;
    public $to;

    public function rules()
    {
        return [
            [['batchId', 'officeId'], 'integer'],
            [['batchType'], 'string'],
            [['batchType'], 'in', 'range' => PayrollBatchType::getEnums()],
            [['from', 'to'], 'date', 'format' => Yii::$app->params['formats'][0]],
        ];
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'batchId' => Yii::t('app', 'Batch No'),
            'officeId' => Yii::t('app', 'Office')
        ]);
    }

    public function apply(Query $query)
    {
        if ($this->batchId && $this->validate('batchId')) {
            $query->andWhere(['t.payroll_batch_id' => $this->batchId]);
        }
        if ($this->batchType && $this->validate('batchType')) {
            $query->andWhere(['pb.type' => $this->batchType]);
        }
        if ($this->officeId && $this->validate('officeId')) {
            $query->andWhere(['t.office_id' => $this->officeId]);
        }
        if ($this->from && $this->validate('from')) {
            $query->andWhere(['<=', 'pb.period_start', DateTime::transformDate($this->from, Yii::$app->params['formats'][0], Yii::$app->params['formats']['db'])]);
        }
        if ($this->to && $this->validate('to')) {
            $query->andWhere(['>=', 'pb.period_end', DateTime::transformDate($this->to, Yii::$app->params['formats'][0], Yii::$app->params['formats']['db'])]);
        }
    }
}
