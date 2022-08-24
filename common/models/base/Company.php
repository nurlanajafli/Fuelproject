<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace common\models\base;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "company".
 *
 * @property integer $id
 * @property string $name
 * @property string $address_1
 * @property string $address_2
 * @property string $city
 * @property integer $state_id
 * @property string $zip
 * @property string $country
 * @property string $main_phone
 * @property string $main_800
 * @property string $main_fax
 * @property string $accounting_phone
 * @property string $ar_contact
 * @property string $ap_contact
 * @property string $business_type
 * @property string $federal_id
 * @property string $dot_id
 * @property string $mc_id
 * @property string $scac
 * @property string $logo
 * @property integer $business_direction_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property \common\models\BusinessDirection $businessDirection
 * @property \common\models\State $state
 * @property \common\models\User $createdBy
 * @property \common\models\User $updatedBy
 * @property \common\models\CompanyBusinessDirection[] $companyBusinessDirections
 * @property \common\models\BusinessDirection[] $businessDirections
 * @property string $aliasModel
 */
abstract class Company extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
            ],
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'address_1', 'city', 'zip', 'country', 'main_phone', 'accounting_phone', 'ar_contact', 'ap_contact', 'business_type', 'federal_id', 'dot_id', 'mc_id', 'scac', 'business_direction_id'], 'required'],
            [['state_id', 'business_direction_id'], 'default', 'value' => null],
            [['state_id', 'business_direction_id'], 'integer'],
            [['name', 'address_1', 'address_2', 'city', 'country', 'main_phone', 'main_800', 'main_fax', 'accounting_phone', 'ar_contact', 'ap_contact', 'business_type', 'federal_id', 'dot_id', 'mc_id', 'scac', 'logo'], 'string', 'max' => 255],
            [['zip'], 'string', 'max' => 10],
            [['business_direction_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\BusinessDirection::className(), 'targetAttribute' => ['business_direction_id' => 'id']],
            [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\State::className(), 'targetAttribute' => ['state_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\User::className(), 'targetAttribute' => ['updated_by' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'address_1' => Yii::t('app', 'Address 1'),
            'address_2' => Yii::t('app', 'Address 2'),
            'city' => Yii::t('app', 'City'),
            'state_id' => Yii::t('app', 'State ID'),
            'zip' => Yii::t('app', 'Zip'),
            'country' => Yii::t('app', 'Country'),
            'main_phone' => Yii::t('app', 'Main Phone'),
            'main_800' => Yii::t('app', 'Main 800'),
            'main_fax' => Yii::t('app', 'Main Fax'),
            'accounting_phone' => Yii::t('app', 'Accounting Phone'),
            'ar_contact' => Yii::t('app', 'Ar Contact'),
            'ap_contact' => Yii::t('app', 'Ap Contact'),
            'business_type' => Yii::t('app', 'Business Type'),
            'federal_id' => Yii::t('app', 'Federal ID'),
            'dot_id' => Yii::t('app', 'Dot ID'),
            'mc_id' => Yii::t('app', 'Mc ID'),
            'scac' => Yii::t('app', 'Scac'),
            'logo' => Yii::t('app', 'Logo'),
            'business_direction_id' => Yii::t('app', 'Business Direction ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessDirection()
    {
        return $this->hasOne(\common\models\BusinessDirection::className(), ['id' => 'business_direction_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(\common\models\State::className(), ['id' => 'state_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyBusinessDirections()
    {
        return $this->hasMany(\common\models\CompanyBusinessDirection::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessDirections()
    {
        return $this->hasMany(\common\models\BusinessDirection::className(), ['id' => 'business_direction_id'])->viaTable('company_business_direction', ['company_id' => 'id']);
    }




}
