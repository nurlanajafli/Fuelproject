<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace common\models\base;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "dispatch_assignment".
 *
 * @property integer $id
 * @property integer $load_id
 * @property string $pay_code
 * @property string $date
 * @property integer $unit_id
 * @property integer $driver_id
 * @property integer $codriver_id
 * @property integer $truck_id
 * @property integer $trailer_id
 * @property integer $trailer2_id
 * @property string $notes
 * @property string $driver_pay_source
 * @property integer $driver_pay_matrix
 * @property string $driver_pay_type
 * @property integer $driver_loaded_miles
 * @property integer $driver_empty_miles
 * @property string $driver_loaded_rate
 * @property string $driver_empty_rate
 * @property string $driver_total_pay
 * @property string $codriver_pay_source
 * @property integer $codriver_pay_matrix
 * @property string $codriver_pay_type
 * @property integer $codriver_loaded_miles
 * @property integer $codriver_empty_miles
 * @property string $codriver_loaded_rate
 * @property string $codriver_empty_rate
 * @property string $codriver_total_pay
 * @property string $dispatch_start_date
 * @property string $dispatch_start_time_in
 * @property string $dispatch_start_time_out
 * @property string $dispatch_deliver_date
 * @property string $dispatch_deliver_time_in
 * @property string $dispatch_deliver_time_out
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \common\models\Driver $driver
 * @property \common\models\Driver $codriver
 * @property \common\models\Load $load
 * @property \common\models\Trailer $trailer
 * @property \common\models\Trailer $trailer2
 * @property \common\models\Truck $truck
 * @property \common\models\Unit $unit
 * @property \common\models\User $createdBy
 * @property \common\models\User $updatedBy
 * @property string $aliasModel
 */
abstract class DispatchAssignment extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dispatch_assignment';
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
            [['load_id', 'pay_code', 'date', 'unit_id', 'driver_id', 'truck_id', 'driver_pay_source', 'driver_pay_type'], 'required'],
            [['load_id', 'unit_id', 'driver_id', 'codriver_id', 'truck_id', 'trailer_id', 'trailer2_id', 'driver_pay_matrix', 'driver_loaded_miles', 'driver_empty_miles', 'codriver_pay_matrix', 'codriver_loaded_miles', 'codriver_empty_miles'], 'default', 'value' => null],
            [['load_id', 'unit_id', 'driver_id', 'codriver_id', 'truck_id', 'trailer_id', 'trailer2_id', 'driver_pay_matrix', 'driver_loaded_miles', 'driver_empty_miles', 'codriver_pay_matrix', 'codriver_loaded_miles', 'codriver_empty_miles'], 'integer'],
            [['date', 'dispatch_start_date', 'dispatch_start_time_in', 'dispatch_start_time_out', 'dispatch_deliver_date', 'dispatch_deliver_time_in', 'dispatch_deliver_time_out'], 'safe'],
            [['notes'], 'string'],
            [['driver_loaded_rate', 'driver_empty_rate', 'driver_total_pay', 'codriver_loaded_rate', 'codriver_empty_rate', 'codriver_total_pay'], 'number'],
            [['pay_code', 'driver_pay_source', 'driver_pay_type', 'codriver_pay_source', 'codriver_pay_type'], 'string', 'max' => 255],
            [['load_id'], 'unique'],
            [['driver_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Driver::className(), 'targetAttribute' => ['driver_id' => 'id']],
            [['codriver_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Driver::className(), 'targetAttribute' => ['codriver_id' => 'id']],
            [['load_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Load::className(), 'targetAttribute' => ['load_id' => 'id']],
            [['trailer_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Trailer::className(), 'targetAttribute' => ['trailer_id' => 'id']],
            [['trailer2_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Trailer::className(), 'targetAttribute' => ['trailer2_id' => 'id']],
            [['truck_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Truck::className(), 'targetAttribute' => ['truck_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Unit::className(), 'targetAttribute' => ['unit_id' => 'id']],
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
            'load_id' => Yii::t('app', 'Load ID'),
            'pay_code' => Yii::t('app', 'Pay Code'),
            'date' => Yii::t('app', 'Date'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'driver_id' => Yii::t('app', 'Driver ID'),
            'codriver_id' => Yii::t('app', 'Codriver ID'),
            'truck_id' => Yii::t('app', 'Truck ID'),
            'trailer_id' => Yii::t('app', 'Trailer ID'),
            'trailer2_id' => Yii::t('app', 'Trailer2 ID'),
            'notes' => Yii::t('app', 'Notes'),
            'driver_pay_source' => Yii::t('app', 'Driver Pay Source'),
            'driver_pay_matrix' => Yii::t('app', 'Driver Pay Matrix'),
            'driver_pay_type' => Yii::t('app', 'Driver Pay Type'),
            'driver_loaded_miles' => Yii::t('app', 'Driver Loaded Miles'),
            'driver_empty_miles' => Yii::t('app', 'Driver Empty Miles'),
            'driver_loaded_rate' => Yii::t('app', 'Driver Loaded Rate'),
            'driver_empty_rate' => Yii::t('app', 'Driver Empty Rate'),
            'driver_total_pay' => Yii::t('app', 'Driver Total Pay'),
            'codriver_pay_source' => Yii::t('app', 'Codriver Pay Source'),
            'codriver_pay_matrix' => Yii::t('app', 'Codriver Pay Matrix'),
            'codriver_pay_type' => Yii::t('app', 'Codriver Pay Type'),
            'codriver_loaded_miles' => Yii::t('app', 'Codriver Loaded Miles'),
            'codriver_empty_miles' => Yii::t('app', 'Codriver Empty Miles'),
            'codriver_loaded_rate' => Yii::t('app', 'Codriver Loaded Rate'),
            'codriver_empty_rate' => Yii::t('app', 'Codriver Empty Rate'),
            'codriver_total_pay' => Yii::t('app', 'Codriver Total Pay'),
            'dispatch_start_date' => Yii::t('app', 'Dispatch Start Date'),
            'dispatch_start_time_in' => Yii::t('app', 'Dispatch Start Time In'),
            'dispatch_start_time_out' => Yii::t('app', 'Dispatch Start Time Out'),
            'dispatch_deliver_date' => Yii::t('app', 'Dispatch Deliver Date'),
            'dispatch_deliver_time_in' => Yii::t('app', 'Dispatch Deliver Time In'),
            'dispatch_deliver_time_out' => Yii::t('app', 'Dispatch Deliver Time Out'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDriver()
    {
        return $this->hasOne(\common\models\Driver::className(), ['id' => 'driver_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodriver()
    {
        return $this->hasOne(\common\models\Driver::className(), ['id' => 'codriver_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoad()
    {
        return $this->hasOne(\common\models\Load::className(), ['id' => 'load_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrailer()
    {
        return $this->hasOne(\common\models\Trailer::className(), ['id' => 'trailer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrailer2()
    {
        return $this->hasOne(\common\models\Trailer::className(), ['id' => 'trailer2_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTruck()
    {
        return $this->hasOne(\common\models\Truck::className(), ['id' => 'truck_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(\common\models\Unit::className(), ['id' => 'unit_id']);
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




}
