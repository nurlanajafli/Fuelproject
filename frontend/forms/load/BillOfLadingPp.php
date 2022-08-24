<?php


namespace frontend\forms\load;

use common\enums\BillOfLadingType;
use Yii;
use yii\base\Model;

class BillOfLadingPp extends Model
{
    public $freightChargesType;
    public $viewType;
    public $billingNoticeType;
    public $carrierNameType;
    public $phoneNumbersType;

    public function rules() {
        return [
            [['freightChargesType','viewType','billingNoticeType','carrierNameType','phoneNumbersType'], 'required'],
            [['freightChargesType','viewType','billingNoticeType','carrierNameType','phoneNumbersType'], 'string'],
            [['freightChargesType'], 'in', 'range' => [BillOfLadingType::SHOW_FREIGHT_CHARGES,BillOfLadingType::HIDE_FREIGHT_CHARGES]],
            [['viewType'], 'in', 'range' => [BillOfLadingType::STANDART_VIEW,BillOfLadingType::PREMIUM_VIEW]],
            [['billingNoticeType'], 'in', 'range' =>  [BillOfLadingType::SHOW_BILLING_NOTICE,BillOfLadingType::HIDE_BILLING_NOTICE]],
            [['carrierNameType'], 'in', 'range' => [BillOfLadingType::SHOW_CARRIER_NAME,BillOfLadingType::HIDE_CARRIER_NAME]],
            [['phoneNumbersType'], 'in', 'range' => [BillOfLadingType::SHOW_PHONE_NUMBERS,BillOfLadingType::HIDE_PHONE_NUMBERS]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'freightChargesType' => Yii::t('app', 'Select'),
            'viewType' => Yii::t('app', 'Select'),
            'billingNoticeType' => Yii::t('app', 'Select'),
            'carrierNameType' => Yii::t('app', 'Select'),
            'phoneNumbersType' => Yii::t('app', 'Select')
        ];
    }

}