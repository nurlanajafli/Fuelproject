<?php

namespace frontend\forms\bill;

use common\models\Account;
use common\models\Office;
use yii\base\Model;
use Yii;

class AdjustBalance extends Model
{
    public $date;
    public $glAccount;
    public $office;
    public $amount;
    public $ourRef;
    public $udf;
    public $memo;

    public function rules()
    {
        return [
            [['date', 'glAccount', 'amount',], 'required'],
            [['date',], 'date', 'format' => Yii::$app->params['formats']['db']],
            [['glAccount'], 'exist', 'targetClass' => Account::class, 'targetAttribute' => ['glAccount' => 'account']],
            [['office'], 'exist', 'targetClass' => Office::class, 'targetAttribute' => ['office' => 'id']],
            [['amount'], 'number',],
            [['amount'], function ($attribute, $params, $validator) {
                if (!$this->hasErrors($attribute)) {
                    if ($this->$attribute == 0) {
                        $this->addError($attribute, Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel($attribute)]));
                    }
                }
            },],
            [['ourRef', 'udf', 'memo'], 'string',],
        ];
    }

    public function attributeLabels()
    {
        return [
            'date' => Yii::t('app', 'Date'),
            'glAccount' => Yii::t('app', 'GL Acct'),
            'office' => Yii::t('app', 'Office'),
            'amount' => Yii::t('app', 'Amount'),
            'ourRef' => Yii::t('app', 'Our Ref'),
            'udf' => Yii::t('app', 'UDF'),
            'memo' => Yii::t('app', 'Memo'),
        ];
    }
}
