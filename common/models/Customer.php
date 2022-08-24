<?php

namespace common\models;

use common\helpers\DateTime;
use common\helpers\Utils;
use common\models\base\Customer as BaseCustomer;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "customer".
 */
class Customer extends BaseCustomer
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function rules()
    {
        $rules = Utils::removeAttributeRules(parent::rules(), 'other_bill_to', ['exist']);
        return ArrayHelper::merge(
            $rules,
            [
                [['rate_source'], 'in', 'range' => \common\enums\RateSource::getEnums()],
                [['system'], 'in', 'range' => \common\enums\System::getEnums()],
                [['calc_by'], 'in', 'range' => \common\enums\CustomerCalcBy::getEnums()],
                [['pay_type_1'], 'in', 'range' => \common\enums\CustomerPayType::getEnums()],
                [['pay_type_2'], 'in', 'range' => \common\enums\CustomerPayType::getEnums()],
                [['invoice_style'], 'in', 'range' => \common\enums\InvoiceStyle::getEnums()],
                [['invoicing_method'], 'in', 'range' => \common\enums\InvoicingMethod::getEnums()],
                [
                    ['other_bill_to'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => static::class,
                    'targetAttribute' => ['other_bill_to' => 'id'],
                    'filter' => function ($query) {
                        /** @var Query $query */
                        $query->andWhere(['<>', 'id', $this->id]);
                    }
                ],
            ]
        );
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'main_phone' => Yii::t('app', 'Phone'),
            'main_800' => Yii::t('app', '800'),
            'main_fax' => Yii::t('app', 'Fax'),
            'ap_contact' => Yii::t('app', 'A/P Contact'),
            'mc_id' => Yii::t('app', 'MCID'),
            'scac' => Yii::t('app', 'SCAC'),
            'state_id' => Yii::t('app', 'State'),
            'acc_matrix_id' => Yii::t('app', 'Acc Matrix'),
            'pay_type_1' => Yii::t('app', 'Pay Type'),
            'rate_1' => Yii::t('app', 'Rate'),
            'pay_type_2' => Yii::t('app', 'Pay Type'),
            'rate_2' => Yii::t('app', 'Rate'),
            'send_invoices_as_pdf_attachments' => Yii::t('app', 'Send Invoices As PDF Attachments'),
            'pre_billing_allowed_no_backup_required' => Yii::t('app', 'Pre-Billing Allowed - No Backup Required'),
        ]);
    }

    public function get_dropdownValue()
    {
        return implode(" - ", [$this->name, $this->address_1, $this->city, $this->zip, $this->state->state_code, $this->main_phone]);
    }
}
