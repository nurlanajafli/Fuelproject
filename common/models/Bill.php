<?php

namespace common\models;

use common\enums\BillStatus;
use common\helpers\DateTime;
use common\models\base\Bill as BaseBill;
use Yii;
use yii\base\Event;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bill".
 *
 * @property-read double $amount
 * @property-read double $balance
 */
class Bill extends BaseBill
{
    public function init()
    {
        parent::init();

        $this->on(static::EVENT_BEFORE_DELETE, function (Event $event) {
            foreach ([BillItem::tableName(), Payment::tableName()] as $table) {
                $command = static::getDb()->createCommand();
                $command->delete($table, ['bill_id' => $this->id]);
                $command->execute();
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
            ['ap_account', 'default', 'value' => null]
        ]);
    }

    public function getAmount()
    {
        return $this->getBillItems()->sum('amount') + 0;
    }

    public function getBalance()
    {
        return $this->amount - $this->getPayments()->sum('amount');
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'from_carrier_id' => Yii::t('app', 'From'),
            'from_vendor_id' => Yii::t('app', 'From'),
            'ap_account' => Yii::t('app', 'A/P Acct'),
            'payment_terms' => Yii::t('app', 'Terms'),
            'office_id' => Yii::t('app', 'Office'),
        ]);
    }

    public function canDelete()
    {
        return ($this->status == BillStatus::OPEN) && !$this->payments;
    }
}
