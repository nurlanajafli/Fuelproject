<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace common\models\base;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "trailer".
 *
 * @property integer $id
 * @property string $type
 * @property integer $year
 * @property string $make
 * @property string $model
 * @property integer $tare
 * @property integer $length
 * @property integer $height
 * @property string $in_svc
 * @property string $status
 * @property string $serial
 * @property string $license
 * @property integer $license_state_id
 * @property string $carb_id
 * @property integer $office_id
 * @property string $notes
 * @property string $out_of_service
 * @property string $marked_as_down
 * @property string $trailer_no
 * @property string $company_owned_leased
 * @property integer $owned_by_carrier_id
 * @property integer $owned_by_customer_id
 * @property integer $owned_by_driver_id
 * @property integer $owned_by_vendor_id
 * @property boolean $purchased_new
 * @property string $purchase_date
 * @property string $purchase_price
 * @property boolean $insured
 * @property string $insured_date
 * @property string $insured_value
 * @property string $annual_premium
 * @property string $depreciated_value
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $created_by
 * @property string $created_at
 *
 * @property \common\models\DispatchAssignment[] $dispatchAssignments
 * @property \common\models\DispatchAssignment[] $dispatchAssignments0
 * @property \common\models\Document[] $documents
 * @property \common\models\FuelPurchase[] $fuelPurchases
 * @property \common\models\FuelPurchase[] $fuelPurchases0
 * @property \common\models\LoadDrop[] $loadDrops
 * @property \common\models\LoadDrop[] $loadDrops0
 * @property \common\models\LoadDrop[] $loadDrops1
 * @property \common\models\LoadDrop[] $loadDrops2
 * @property \common\models\LoadMovement[] $loadMovements
 * @property \common\models\Report[] $reports
 * @property \common\models\TrackingLog[] $trackingLogs
 * @property \common\models\Carrier $ownedByCarrier
 * @property \common\models\Customer $ownedByCustomer
 * @property \common\models\Driver $ownedByDriver
 * @property \common\models\Office $office
 * @property \common\models\State $licenseState
 * @property \common\models\TrailerType $type0
 * @property \common\models\Vendor $ownedByVendor
 * @property \common\models\Unit[] $units
 * @property \common\models\Unit[] $units0
 * @property string $aliasModel
 */
abstract class Trailer extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trailer';
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
            [['type', 'status', 'trailer_no'], 'required'],
            [['year', 'tare', 'length', 'height', 'license_state_id', 'office_id', 'owned_by_carrier_id', 'owned_by_customer_id', 'owned_by_driver_id', 'owned_by_vendor_id'], 'default', 'value' => null],
            [['year', 'tare', 'length', 'height', 'license_state_id', 'office_id', 'owned_by_carrier_id', 'owned_by_customer_id', 'owned_by_driver_id', 'owned_by_vendor_id'], 'integer'],
            [['in_svc', 'out_of_service', 'marked_as_down', 'purchase_date', 'insured_date'], 'safe'],
            [['notes'], 'string'],
            [['purchased_new', 'insured'], 'boolean'],
            [['purchase_price', 'insured_value', 'annual_premium', 'depreciated_value'], 'number'],
            [['type', 'make', 'model', 'status', 'serial', 'license', 'carb_id', 'trailer_no', 'company_owned_leased'], 'string', 'max' => 255],
            [['owned_by_carrier_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Carrier::className(), 'targetAttribute' => ['owned_by_carrier_id' => 'id']],
            [['owned_by_customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Customer::className(), 'targetAttribute' => ['owned_by_customer_id' => 'id']],
            [['owned_by_driver_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Driver::className(), 'targetAttribute' => ['owned_by_driver_id' => 'id']],
            [['office_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Office::className(), 'targetAttribute' => ['office_id' => 'id']],
            [['license_state_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\State::className(), 'targetAttribute' => ['license_state_id' => 'id']],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\TrailerType::className(), 'targetAttribute' => ['type' => 'type']],
            [['owned_by_vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Vendor::className(), 'targetAttribute' => ['owned_by_vendor_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'year' => Yii::t('app', 'Year'),
            'make' => Yii::t('app', 'Make'),
            'model' => Yii::t('app', 'Model'),
            'tare' => Yii::t('app', 'Tare'),
            'length' => Yii::t('app', 'Length'),
            'height' => Yii::t('app', 'Height'),
            'in_svc' => Yii::t('app', 'In Svc'),
            'status' => Yii::t('app', 'Status'),
            'serial' => Yii::t('app', 'Serial'),
            'license' => Yii::t('app', 'License'),
            'license_state_id' => Yii::t('app', 'License State ID'),
            'carb_id' => Yii::t('app', 'Carb ID'),
            'office_id' => Yii::t('app', 'Office ID'),
            'notes' => Yii::t('app', 'Notes'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'out_of_service' => Yii::t('app', 'Out Of Service'),
            'marked_as_down' => Yii::t('app', 'Marked As Down'),
            'trailer_no' => Yii::t('app', 'Trailer No'),
            'company_owned_leased' => Yii::t('app', 'Company Owned Leased'),
            'owned_by_carrier_id' => Yii::t('app', 'Owned By Carrier ID'),
            'owned_by_customer_id' => Yii::t('app', 'Owned By Customer ID'),
            'owned_by_driver_id' => Yii::t('app', 'Owned By Driver ID'),
            'owned_by_vendor_id' => Yii::t('app', 'Owned By Vendor ID'),
            'purchased_new' => Yii::t('app', 'Purchased New'),
            'purchase_date' => Yii::t('app', 'Purchase Date'),
            'purchase_price' => Yii::t('app', 'Purchase Price'),
            'insured' => Yii::t('app', 'Insured'),
            'insured_date' => Yii::t('app', 'Insured Date'),
            'insured_value' => Yii::t('app', 'Insured Value'),
            'annual_premium' => Yii::t('app', 'Annual Premium'),
            'depreciated_value' => Yii::t('app', 'Depreciated Value'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDispatchAssignments()
    {
        return $this->hasMany(\common\models\DispatchAssignment::className(), ['trailer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDispatchAssignments0()
    {
        return $this->hasMany(\common\models\DispatchAssignment::className(), ['trailer2_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(\common\models\Document::className(), ['trailer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFuelPurchases()
    {
        return $this->hasMany(\common\models\FuelPurchase::className(), ['trailer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFuelPurchases0()
    {
        return $this->hasMany(\common\models\FuelPurchase::className(), ['trailer2_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoadDrops()
    {
        return $this->hasMany(\common\models\LoadDrop::className(), ['drop_trailer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoadDrops0()
    {
        return $this->hasMany(\common\models\LoadDrop::className(), ['drop_trailer_2_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoadDrops1()
    {
        return $this->hasMany(\common\models\LoadDrop::className(), ['retrieve_trailer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoadDrops2()
    {
        return $this->hasMany(\common\models\LoadDrop::className(), ['retrieve_trailer_2_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoadMovements()
    {
        return $this->hasMany(\common\models\LoadMovement::className(), ['trailer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReports()
    {
        return $this->hasMany(\common\models\Report::className(), ['trailer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrackingLogs()
    {
        return $this->hasMany(\common\models\TrackingLog::className(), ['trailer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwnedByCarrier()
    {
        return $this->hasOne(\common\models\Carrier::className(), ['id' => 'owned_by_carrier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwnedByCustomer()
    {
        return $this->hasOne(\common\models\Customer::className(), ['id' => 'owned_by_customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwnedByDriver()
    {
        return $this->hasOne(\common\models\Driver::className(), ['id' => 'owned_by_driver_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffice()
    {
        return $this->hasOne(\common\models\Office::className(), ['id' => 'office_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLicenseState()
    {
        return $this->hasOne(\common\models\State::className(), ['id' => 'license_state_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType0()
    {
        return $this->hasOne(\common\models\TrailerType::className(), ['type' => 'type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwnedByVendor()
    {
        return $this->hasOne(\common\models\Vendor::className(), ['id' => 'owned_by_vendor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnits()
    {
        return $this->hasMany(\common\models\Unit::className(), ['trailer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnits0()
    {
        return $this->hasMany(\common\models\Unit::className(), ['trailer_2_id' => 'id']);
    }




}
