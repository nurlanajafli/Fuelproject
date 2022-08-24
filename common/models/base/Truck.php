<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace common\models\base;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "truck".
 *
 * @property integer $id
 * @property string $type
 * @property integer $year
 * @property string $make
 * @property string $model
 * @property integer $tare
 * @property string $in_svc
 * @property string $serial
 * @property string $vin
 * @property string $status
 * @property string $license
 * @property integer $license_state_id
 * @property string $carb_id
 * @property integer $office_id
 * @property string $notes
 * @property string $out_of_service
 * @property boolean $is_down
 * @property string $returns_to_service
 * @property integer $return_location_id
 * @property boolean $notify_all_dispatch_personnel
 * @property string $downed_at
 * @property integer $downed_by
 * @property string $truck_no
 * @property string $company_owned_leased
 * @property integer $owned_by_carrier_id
 * @property integer $owned_by_customer_id
 * @property integer $owned_by_vendor_id
 * @property boolean $purchased_new
 * @property string $purchase_date
 * @property string $purchase_price
 * @property boolean $non_ifta
 * @property string $ny_permit
 * @property integer $ny_gross
 * @property integer $or_decl_wgt
 * @property string $or_decl_axles
 * @property boolean $insured
 * @property string $insured_date
 * @property string $insured_value
 * @property string $annual_premium
 * @property string $depreciated_value
 * @property integer $owned_by_driver_id
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $created_by
 * @property string $created_at
 *
 * @property \common\models\DispatchAssignment[] $dispatchAssignments
 * @property \common\models\Document[] $documents
 * @property \common\models\DriverAdjustment[] $driverAdjustments
 * @property \common\models\FuelPurchase[] $fuelPurchases
 * @property \common\models\LoadDrop[] $loadDrops
 * @property \common\models\LoadDrop[] $loadDrops0
 * @property \common\models\LoadMovement[] $loadMovements
 * @property \common\models\TrackingLog[] $trackingLogs
 * @property \common\models\Carrier $ownedByCarrier
 * @property \common\models\Customer $ownedByCustomer
 * @property \common\models\Driver $ownedByDriver
 * @property \common\models\Location $returnLocation
 * @property \common\models\Office $office
 * @property \common\models\State $licenseState
 * @property \common\models\TruckType $type0
 * @property \common\models\User $downedBy
 * @property \common\models\Vendor $ownedByVendor
 * @property \common\models\TruckOdometer[] $truckOdometers
 * @property \common\models\Unit[] $units
 * @property string $aliasModel
 */
abstract class Truck extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'truck';
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
            [['type', 'status', 'truck_no'], 'required'],
            [['year', 'tare', 'license_state_id', 'office_id', 'return_location_id', 'downed_by', 'owned_by_carrier_id', 'owned_by_customer_id', 'owned_by_vendor_id', 'ny_gross', 'or_decl_wgt', 'owned_by_driver_id'], 'default', 'value' => null],
            [['year', 'tare', 'license_state_id', 'office_id', 'return_location_id', 'downed_by', 'owned_by_carrier_id', 'owned_by_customer_id', 'owned_by_vendor_id', 'ny_gross', 'or_decl_wgt', 'owned_by_driver_id'], 'integer'],
            [['in_svc', 'out_of_service', 'returns_to_service', 'downed_at', 'purchase_date', 'insured_date'], 'safe'],
            [['notes'], 'string'],
            [['is_down', 'notify_all_dispatch_personnel', 'purchased_new', 'non_ifta', 'insured'], 'boolean'],
            [['purchase_price', 'insured_value', 'annual_premium', 'depreciated_value'], 'number'],
            [['type', 'make', 'model', 'serial', 'vin', 'status', 'license', 'carb_id', 'truck_no', 'company_owned_leased', 'ny_permit', 'or_decl_axles'], 'string', 'max' => 255],
            [['owned_by_carrier_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Carrier::className(), 'targetAttribute' => ['owned_by_carrier_id' => 'id']],
            [['owned_by_customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Customer::className(), 'targetAttribute' => ['owned_by_customer_id' => 'id']],
            [['owned_by_driver_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Driver::className(), 'targetAttribute' => ['owned_by_driver_id' => 'id']],
            [['return_location_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Location::className(), 'targetAttribute' => ['return_location_id' => 'id']],
            [['office_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Office::className(), 'targetAttribute' => ['office_id' => 'id']],
            [['license_state_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\State::className(), 'targetAttribute' => ['license_state_id' => 'id']],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\TruckType::className(), 'targetAttribute' => ['type' => 'type']],
            [['downed_by'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\User::className(), 'targetAttribute' => ['downed_by' => 'id']],
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
            'in_svc' => Yii::t('app', 'In Svc'),
            'serial' => Yii::t('app', 'Serial'),
            'vin' => Yii::t('app', 'Vin'),
            'status' => Yii::t('app', 'Status'),
            'license' => Yii::t('app', 'License'),
            'license_state_id' => Yii::t('app', 'License State ID'),
            'carb_id' => Yii::t('app', 'Carb ID'),
            'office_id' => Yii::t('app', 'Office ID'),
            'notes' => Yii::t('app', 'Notes'),
            'out_of_service' => Yii::t('app', 'Out Of Service'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'is_down' => Yii::t('app', 'Is Down'),
            'returns_to_service' => Yii::t('app', 'Returns To Service'),
            'return_location_id' => Yii::t('app', 'Return Location ID'),
            'notify_all_dispatch_personnel' => Yii::t('app', 'Notify All Dispatch Personnel'),
            'downed_at' => Yii::t('app', 'Downed At'),
            'downed_by' => Yii::t('app', 'Downed By'),
            'truck_no' => Yii::t('app', 'Truck No'),
            'company_owned_leased' => Yii::t('app', 'Company Owned Leased'),
            'owned_by_carrier_id' => Yii::t('app', 'Owned By Carrier ID'),
            'owned_by_customer_id' => Yii::t('app', 'Owned By Customer ID'),
            'owned_by_vendor_id' => Yii::t('app', 'Owned By Vendor ID'),
            'purchased_new' => Yii::t('app', 'Purchased New'),
            'purchase_date' => Yii::t('app', 'Purchase Date'),
            'purchase_price' => Yii::t('app', 'Purchase Price'),
            'non_ifta' => Yii::t('app', 'Non Ifta'),
            'ny_permit' => Yii::t('app', 'Ny Permit'),
            'ny_gross' => Yii::t('app', 'Ny Gross'),
            'or_decl_wgt' => Yii::t('app', 'Or Decl Wgt'),
            'or_decl_axles' => Yii::t('app', 'Or Decl Axles'),
            'insured' => Yii::t('app', 'Insured'),
            'insured_date' => Yii::t('app', 'Insured Date'),
            'insured_value' => Yii::t('app', 'Insured Value'),
            'annual_premium' => Yii::t('app', 'Annual Premium'),
            'depreciated_value' => Yii::t('app', 'Depreciated Value'),
            'owned_by_driver_id' => Yii::t('app', 'Owned By Driver ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDispatchAssignments()
    {
        return $this->hasMany(\common\models\DispatchAssignment::className(), ['truck_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(\common\models\Document::className(), ['truck_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDriverAdjustments()
    {
        return $this->hasMany(\common\models\DriverAdjustment::className(), ['truck_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFuelPurchases()
    {
        return $this->hasMany(\common\models\FuelPurchase::className(), ['truck_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoadDrops()
    {
        return $this->hasMany(\common\models\LoadDrop::className(), ['drop_truck_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoadDrops0()
    {
        return $this->hasMany(\common\models\LoadDrop::className(), ['retrieve_truck_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoadMovements()
    {
        return $this->hasMany(\common\models\LoadMovement::className(), ['truck_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrackingLogs()
    {
        return $this->hasMany(\common\models\TrackingLog::className(), ['truck_id' => 'id']);
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
    public function getReturnLocation()
    {
        return $this->hasOne(\common\models\Location::className(), ['id' => 'return_location_id']);
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
        return $this->hasOne(\common\models\TruckType::className(), ['type' => 'type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDownedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'downed_by']);
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
    public function getTruckOdometers()
    {
        return $this->hasMany(\common\models\TruckOdometer::className(), ['truck_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnits()
    {
        return $this->hasMany(\common\models\Unit::className(), ['truck_id' => 'id']);
    }




}
