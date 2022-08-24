<?php

namespace frontend\forms\fuel;

use common\enums\FuelChargeTarget;
use common\helpers\DateTime;
use yii\base\Model;

class ImportFuelForm extends Model
{
    public $data_file;
    public $us_vendor;
    public $cn_vendor;
    public $start_date;
    public $end_date;
    public $separate_by_date;
    public $pct;
    public $account_id;
    public $charge_to;
    public $provider;

    public function rules()
    {
        return [
            [['data_file', 'end_date', 'pct', 'provider', 'charge_to'], 'required'],
            [['us_vendor', 'cn_vendor', 'start_date', 'separate_by_date', 'account_id', ], 'safe'],
        ];
    }


    public function attributeLabels()
    {
        return
            array_merge(parent::attributeLabels(), [
                'us_vendor' => \Yii::t('app', 'US Vendor'),
                'cn_vendor' => \Yii::t('app', 'CN Vendor'),
            ]);
    }

    public function __construct($config = [])
    {
        $this->end_date = DateTime::yerterdayDateYMD();
        $this->charge_to = FuelChargeTarget::TRUCK;
        $this->pct = 0;
        parent::__construct($config);
    }


}