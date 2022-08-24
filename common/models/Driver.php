<?php

namespace common\models;

use common\enums\DriverType;
use common\enums\Mile;
use common\enums\PaySource;
use common\enums\PayStandard;
use common\enums\PayType;
use common\helpers\DateTime;
use common\helpers\Utils;
use common\models\base\Driver as BaseDriver;
use common\models\traits\Template;
use Yii;
use yii\base\Event;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use common\models\traits\Person;
use common\enums\PayFrequency;

/**
 * This is the model class for table "driver".
 */
class Driver extends BaseDriver
{
    use Person;
    use Template;

    public function init()
    {
        parent::init();

        $fn = function (Event $event) {
            /** @var Driver $model */
            $model = $event->sender;
            if ($model->pay_to_carrier_id) {
                $model->pay_to_driver_id = null;
                $model->pay_to_vendor_id = null;
            } elseif ($model->pay_to_driver_id) {
                $model->pay_to_carrier_id = null;
                $model->pay_to_vendor_id = null;
            } elseif ($model->pay_to_vendor_id) {
                $model->pay_to_carrier_id = null;
                $model->pay_to_driver_id = null;
            }
        };
        $this->on(self::EVENT_BEFORE_INSERT, $fn);
        $this->on(self::EVENT_BEFORE_UPDATE, $fn);
    }

    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function rules()
    {
        $rules = Utils::removeAttributeRules(parent::rules(), 'pay_to_driver_id', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'expense_acct', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'bank_acct', ['exist']);
        $rules = Utils::removeAttributeRules($rules, 'co_driver_id', ['exist']);
        return ArrayHelper::merge(
            $rules,
            [
                [['expense_acct', 'bank_acct'], 'default', 'value' => null],
                [['email_address'], 'email'],
                [['type'], 'in', 'range' => DriverType::getEnums()],
                [['pay_standard'], 'in', 'range' => PayStandard::getEnums()],
                [['pay_source'], 'in', 'range' => PaySource::getEnums()],
                [['loaded_miles', 'empty_miles'], 'in', 'range' => Mile::getEnums()],
                [['loaded_pay_type'], 'in', 'range' => PayType::getEnums()],
                [
                    ['pay_to_driver_id'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => Driver::class,
                    'targetAttribute' => ['pay_to_driver_id' => 'id'],
                    'filter' => function ($query) {
                        /** @var Query $query */
                        $query->andWhere(['<>', 'id', $this->id]);
                    }
                ],
                [
                    ['expense_acct'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => Account::class,
                    'targetAttribute' => ['expense_acct' => 'account'],
                    'filter' => Account::getFilterByType(['Cost Of Sales', 'Expense'])
                ],
                [
                    ['bank_acct'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => Account::class,
                    'targetAttribute' => ['bank_acct' => 'account'],
                    'filter' => Account::getFilterByType('Bank')
                ],
                [
                    ['co_driver_id'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => Driver::class,
                    'targetAttribute' => ['co_driver_id' => 'id'],
                    'filter' => function ($query) {
                        /** @var Query $query */
                        $query->andWhere(['<>', 'id', $this->id]);
                    }
                ],
                [['pay_frequency'], 'in', 'range' => PayFrequency::getEnums()]
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => Yii::t('app', 'ID'),
            'last_name' => Yii::t('app', 'Last Name'),
            'first_name' => Yii::t('app', 'First Name'),
            'middle_name' => Yii::t('app', 'Middle Name'),
            'address_1' => Yii::t('app', 'Address 1'),
            'address_2' => Yii::t('app', 'Address 2'),
            'city' => Yii::t('app', 'City'),
            'state_id' => Yii::t('app', 'State'),
            'zip' => Yii::t('app', 'Zip'),
            'telephone' => Yii::t('app', 'Telephone'),
            'cell_phone' => Yii::t('app', 'Cell Phone'),
            'other_phone' => Yii::t('app', 'Other Phone'),
            'office_id' => Yii::t('app', 'Office'),
            'web_id' => Yii::t('app', 'Web ID'),
            'email_address' => Yii::t('app', 'Email Address'),
            'user_defined_1' => Yii::t('app', 'User Defined 1'),
            'user_defined_2' => Yii::t('app', 'User Defined 2'),
            'user_defined_3' => Yii::t('app', 'User Defined 3'),
            'social_sec_no' => Yii::t('app', 'Social Sec No'),
            'passport_no' => Yii::t('app', 'Passport No'),
            'passport_exp' => Yii::t('app', 'Passport Exp'),
            'date_of_birth' => Yii::t('app', 'Date Of Birth'),
            'hire_date' => Yii::t('app', 'Hire Date'),
            'mail_list' => Yii::t('app', 'Mail List'),
            'maintenance' => Yii::t('app', 'Maintenance'),
            'notes' => Yii::t('app', 'Notes'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'marked_as_down' => Yii::t('app', 'Marked As Down'),
            'type' => Yii::t('app', 'Type'),
            'pay_to_vendor_id' => Yii::t('app', 'Pay To Vendor ID'),
            'pay_to_driver_id' => Yii::t('app', 'Pay To Driver ID'),
            'pay_to_carrier_id' => Yii::t('app', 'Pay To Carrier ID'),
            'expense_acct' => Yii::t('app', 'Expense Acct'),
            'bank_acct' => Yii::t('app', 'Bank Acct'),
            'co_driver_id' => Yii::t('app', 'CoDriver'),
            'pay_standard' => Yii::t('app', 'Pay Standard'),
            'period_salary' => Yii::t('app', 'Period Salary'),
            'hourly_rate' => Yii::t('app', 'Hourly Rate'),
            'addl_ot_pay' => Yii::t('app', 'Addl OT Pay'),
            'addl_ot_pay_2' => Yii::t('app', 'Addl OT Pay 2'),
        ]);
    }

    public function get_label()
    {
        return $this->getFullName();
    }
}
