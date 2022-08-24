<?php

namespace common\models;

use common\enums\CompanyOwnedLeased;
use common\enums\ORDeclAxle;
use common\enums\TruckStatus;
use common\helpers\DateTime;
use common\models\base\Truck as BaseTruck;
use common\models\traits\Template;
use Yii;
use yii\base\Event;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "truck".
 */
class Truck extends BaseTruck
{
    use Template;

    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->status = TruckStatus::AVAILABLE;
        }
        $this->on(self::EVENT_BEFORE_VALIDATE, function (Event $event) {
            /** @var Truck $model */
            $model = $event->sender;
            if ($model->owned_by_carrier_id) {
                $model->owned_by_customer_id = null;
                $model->owned_by_vendor_id = null;
            } elseif ($model->owned_by_customer_id) {
                $model->owned_by_carrier_id = null;
                $model->owned_by_vendor_id = null;
            } elseif ($model->owned_by_vendor_id) {
                $model->owned_by_carrier_id = null;
                $model->owned_by_customer_id = null;
            }
        });
    }

    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['company_owned_leased'], 'in', 'range' => CompanyOwnedLeased::getEnums()],
            [['or_decl_axles'], 'in', 'range' => ORDeclAxle::getEnums()],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => Yii::t('app', 'Truck No'),
            'office_id' => Yii::t('app', 'Office'),
            'company_owned_leased' => Yii::t('app', 'Company Owned/Leased'),
            'non_ifta' => Yii::t('app', 'Non IFTA'),
            'ny_permit' => Yii::t('app', 'NY Permit'),
            'ny_gross' => Yii::t('app', 'NY Gross'),
            'or_decl_wgt' => Yii::t('app', 'OR Decl Wgt'),
            'or_decl_axles' => Yii::t('app', 'OR Decl Axles'),
        ]);
    }

    public function get_label()
    {
        return sprintf('%s (%s %s)', $this->truck_no, $this->make, $this->model);
    }
}
